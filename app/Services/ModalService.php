<?php

namespace App\Services;

use App\Repositories\CameraRepository;
use App\Repositories\ConstantRepository;

use App\Repositories\VideoRepository;
use App\Repositories\ApplicantRepository;
use App\Repositories\ViolationRepository;
use App\Repositories\CheckRepository;
use App\Repositories\ViolationDecreeRepository;
use App\Repositories\ViolationStatusRepository;
use App\Repositories\VoteRepository;
use App\Repositories\RealVoteRepository;
use App\Repositories\VoteOfficialRepository;
use App\Repositories\UikRepository;
use App\Repositories\ViolationCommentRepository;
use App\Repositories\IntermediateVoteOfficialRepository;

use App\Models\Violation;
use App\Models\ViolationStatusChange;
use App\Models\ViolationProtocol;
use App\Models\ViolationAutoText;
use App\Models\ViolationCustomText;
use App\Models\ViolationComment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use PhpOffice\PhpWord\PhpWord;

/**
 * Class ModalService
 * @package App\Services
 */
class ModalService
{
    /**
     * @var CameraRepository
     */
    protected $cameraRepository;

    /**
     * @var ConstantRepository
     */
    protected $constantRepository;

    /**
     * @var VideoRepository
     */
    protected $videoRepository;

    /**
     * @var ApplicantRepository
     */
    protected $applicantRepository;

    /**
     * @var ViolationRepository
     */
    protected $violationRepository;

    /**
     * @var CheckRepository
     */
    protected $checkRepository;

    /**
     * @var ViolationDecreeRepository
     */
    protected $violationDecreeRepository;

    /**
     * @var ViolationStatusRepository
     */
    protected $violationStatusRepository;

    /**
     * @var VoteRepository
     */
    protected $voteRepository;

    /**
     * @var IntermediateVoteOfficialRepository
     */
    protected $IntermediateVoteOfficialRepository;

    /**
     * @var VoteOfficialRepository
     */
    protected $voteOfficialRepository;

    /**
     * @var UikRepository
     */
    protected $uikRepository;

    /**
     * @var ViolationCommentRepository
     */
    protected $violationCommentRepository;

    /**
     * @var RealVoteRepository
     */
    protected $realVoteRepository;

    /**
     * ModalService constructor.
     */
    public function __construct()
    {
        $this->constantRepository = app(ConstantRepository::class);
        $this->cameraRepository = app(CameraRepository::class);
        $this->violationRepository = app(ViolationRepository::class);
        $this->voteOfficialRepository = app(VoteOfficialRepository::class);
        $this->uikRepository = app(UikRepository::class);
        $this->violationStatusRepository = app(ViolationStatusRepository::class);
        $this->applicantRepository = app(ApplicantRepository::class);
        $this->checkRepository = app(CheckRepository::class);
        $this->violationDecreeRepository = app(ViolationDecreeRepository::class);
        $this->violationCommentRepository = app(ViolationCommentRepository::class);
        $this->voteRepository = app(VoteRepository::class);
        $this->realVoteRepository = app(RealVoteRepository::class);
        $this->videoRepository = app(VideoRepository::class);
        $this->IntermediateVoteOfficialRepository = app(IntermediateVoteOfficialRepository::class);
    }

    /**
     * Получаем начальные данные для отображения модалки
     * @param object $request
     * @return \App\Models\Violation
     */
    public function getInitialData(object $request)
    {
        $violation = $this->violationRepository->findViolationForModal($request->violation);
        $type = $violation['type'];
        $violationDateStart = $violation['date_start'];

        if (!$violation['blocked']) {
            Violation::where('violation_id', $request->violation)->update([
                'blocked' => true
            ]);
        }

        return collect([
            'constant' => $this->constantRepository->findConstant(),
            'violation' => $violation,
            'officialVotes' => $type == 7 ? $this->voteOfficialRepository->findOfficialVotesForTip($request->uik) : null,
            'intermediateOfficialVotes' => $type == 6 ? $this->IntermediateVoteOfficialRepository->findIntermediateOfficialVotesForTip($request->uik, $violationDateStart) : null,
            'uik' => $this->uikRepository->findUikForTip($request->uik),
            'violationStatusList' => $this->violationStatusRepository->findViolationStatusList(),
            'claim' => collect([
                'applicantList' => $this->applicantRepository->findApplicantListForModal(),
                'checkEndDate' => $this->checkRepository->findCheckVotingDateEnd($request->check),
                'decreeList' => $this->violationDecreeRepository->findViolationDecreeList($request->check)
            ]),
            'comments' => $this->violationCommentRepository->findComments($request->violation),
            'images' => $this->cameraRepository->findImagesGroupByCameraForModal($request->violation),
            'cameraList' => $this->cameraRepository->findCameraListForModal($request->uik),
            'revisorVotes' => $this->voteRepository->findRevisorVotesForModal($request->uik),
            'realVotes' => $this->realVoteRepository->findRealVotesForModal($request->uik),
            'videoTimes' => $this->videoRepository->findVideoTimesForModalChart($request->check, $request->camera),
            'violationListWithNotVideo' => $this->violationRepository->findViolationListWithNotVideoForModal($request->camera),
        ]);
    }

    /**
     * Меняем статус нарушения
     * @param object $request
     * @return object
     */
    public function updateViolationStatus(object $request): object
    {
        $violation = Violation::find($request->violation);
        $violation->status_id = $request->violationStatus;
        $violation->save();

        $user = Auth::user()->user_id;

        $violationStatusChange = new ViolationStatusChange();
        $violationStatusChange->violation_id = $request->violation;
        $violationStatusChange->changed_by = $user;
        $violationStatusChange->status_id = $request->violationStatus;
        $violationStatusChange->status_datetime = now()->format('Y-m-d H:i:s');
        $violationStatusChange->save();

        return collect([
            'violationForModal' => $this->violationRepository->findViolationForModal($request->violation),
            'violationForEventTable' => $this->violationRepository->findViolationForEventTable($request->check),
        ]);
    }

    /**
     * Меняем блокировку нарушения
     * @param object $request
     * @return object
     */
    public function updateViolationBlocked(object $request): object
    {
        $violation = Violation::find($request->violation);
        $violation->blocked = $request->blocked;
        $violation->save();

        return $this->violationRepository->findViolationForEventTable($request->check);
    }

    /**
     * Генерируем жалобу
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function storeClaim(object $request)
    {
        $uniqueName = uniqid();
        $claimPublicPath = '';

        if ($request->file) {
            $storagePath = Storage::disk('local')->put('/www/files/', $request->file);
            $fileName = Str::after($storagePath, 'files/');
            $claimPublicPath = asset('storage/' . $fileName);
        } else {
            $phpWord = new PhpWord();
            $document = $phpWord->loadTemplate(storage_path('app/www/files/upload_layouts/claim.docx'));
            $document->setValue('d_num', '777'); //номер договора
            // $document->setValue('d_date', '04.10.2014'); //дата договора
            // $document->setValue('last_name', 'Никоненко'); //фамилия
            // $document->setValue('name', 'Сергей'); // имя
            // $document->setValue('surname', 'Васильевич'); // отчество
            // $document = \PhpOffice\PhpWord\IOFactory::createWriter($document, 'Word2007');
            $document->saveAs(storage_path('app/www/files/' . $uniqueName) . '.docx'); //имя заполненного шаблона для сохранения

            $claimPublicPath = asset('storage/' . $uniqueName . '.docx');
        }

        $autoText = ViolationAutoText::find($request->violationType);
        $customText = new ViolationCustomText();

        if ($autoText && $request->content == $autoText->violation_text) {
            $protocolTextId = $autoText->auto_text_id;
        } else {
            $customText->violation_text = $request->content;
            $customText->save();
            $protocolTextId = $customText->custom_text_id;
        }

        $violationProtocolData = [
            'violation_id' => $request->violation,
            'protocol_link' => $claimPublicPath,
            'protocol_datetime' => now()->format('Y-m-d H:i:s'),
            'response_link' => null,
            'response_datetime' => null,
            'text_type' => $autoText && $request->content == $autoText->violation_text ? 'auto' : 'custom',
            'text_id' => $protocolTextId,
        ];

        if ($request->applicant != 'null') {
            $violationProtocolData['applicant_id'] = $request->applicant;
        }

        if ($request->decree != 'null') {
            $violationProtocolData['decree_id'] = $request->decree;
        }

        ViolationProtocol::updateOrCreate(
            ['violation_id' => $request->violation],
            $violationProtocolData

        );

        $this->updateViolationStatus((object)[
            'check' => 1,
            'violation' => $request->violation,
            'violationStatus' => 3
        ]);

        return collect(
            [
                'violation' => $this->violationRepository->findViolationForModal($request->violation),
                'claimPath' => $claimPublicPath
            ]
        );
    }

    /**
     * Отправляем ответ на жалобу
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function uploadClaimResponse(object $request): \Illuminate\Support\Collection
    {
        $storagePath = Storage::disk('local')->put('/www/files/', $request->file);
        $fileName = Str::after($storagePath, 'files/');
        $responsePublicPath = asset('storage/' . $fileName);

        ViolationProtocol::where('violation_id', $request->violation)->update([
            'response_link' => $responsePublicPath,
            'response_datetime' => now()->format('Y-m-d H:i:s'),
        ]);

        $this->updateViolationStatus((object)[
            'check' => 1,
            'violation' => $request->violation,
            'violationStatus' => 5
        ]);

        return collect(
            [
                'violation' => $this->violationRepository->findViolationForModal($request->violation),
                'responsePath' => $responsePublicPath
            ]
        );
    }

    /**
     * Удаляем жалобу и меняем статус события
     * @param int $violationId
     * @return object
     */
    public function destroyClaim(int $violationId): object
    {
        Violation::find($violationId)->update([
            'status_id' => 1
        ]);

        ViolationStatusChange::where('violation_id', $violationId)->delete();

        ViolationProtocol::where('violation_id', $violationId)->update([
            'protocol_link' => null,
            'protocol_datetime' => null,
            'response_link' => null,
            'response_datetime' => null,
        ]);

        return $this->violationRepository->findViolationForModal($violationId);
    }

    /**
     * Создаем комментарий
     * @param object $request
     * @return \Illuminate\Support\Collection;
     */
    public function storeComment(object $request): \Illuminate\Support\Collection
    {
        $comment = new ViolationComment();
        $comment->violation_id = $request->violation;
        $comment->comment = $request->text;
        $comment->user_id = Auth::user()->user_id;
        $comment->datetime = now()->format('Y-m-d H:i:s');
        $comment->save();

        return $this->violationCommentRepository->findComments($request->violation);
    }

    /**
     * Получаем видео
     * @param object $request
     * @return ?\App\Models\Video
     */
    public function getVideo(object $request)
    {
        return $this->videoRepository->findVideoForModal($request->camera, $request);
    }

    /**
     * Получаем видео
     * @param object $request
     * @return ?\Illuminate\Support\Collection
     */
    public function getViolationListWithNoVideo(object $request): ?\Illuminate\Support\Collection
    {
        return $this->violationRepository->findViolationListWithNotVideoForModal($request->camera, $request->duration);
    }
}
