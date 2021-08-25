<?php

namespace App\Services;

use App\Models\BoxesInfo;
use App\Models\BoxRecognition;
use App\Models\BoxRecognitionsImage;
use App\Models\Camera;
use App\Models\Check;
use App\Models\CheckedVideo;
use App\Models\CheckingVideo;
use App\Models\CheckUser;
use App\Models\Image;
use App\Models\IntermediateVotesOfficial;
use App\Models\RealCountedVideo;
use App\Models\RealCountingVideo;
use App\Models\StorageServer;
use App\Models\Uik;
use App\Models\UikLabel;
use App\Models\UikTiming;
use App\Models\Video;
use App\Models\VideosAuthType;
use App\Models\VideosErrored;
use App\Models\VideosProcessing;
use App\Models\VideosToCheck;
use App\Models\VideosToCount;
use App\Models\VideosToFindBox;
use App\Models\VideosToRealCount;
use App\Models\VideosToSelectCam;
use App\Models\Violation;
use App\Models\ViolationAutoText;
use App\Models\ViolationComment;
use App\Models\ViolationDecree;
use App\Models\ViolationImage;
use App\Models\ViolationProtocol;
use App\Models\ViolationsCustomText;
use App\Models\ViolationStatusChange;
use App\Models\Vote;
use App\Models\VotesDeviation;
use App\Models\VotesOfficial;
use App\Models\VoteSum;

use App\Repositories\BoxRecognitionRepository;
use App\Repositories\CameraRepository;
use App\Repositories\CheckRepository;
use App\Repositories\ConstantRepository;
use App\Repositories\UikRepository;
use App\Repositories\ViolationRepository;
use App\Repositories\VoteRepository;
use App\Repositories\VideoAuthTypeRepository;

use League\Csv\Reader;

/**
 * Class CheckService
 * @package App\Services
 */
class CheckService
{
    /**
     * @var
     */
    protected $uiks;
    /**
     * @var CheckRepository
     */
    protected $checkRepository;

    /**
     * @var UikRepository
     */
    protected $uikRepository;

    /**
     * @var CameraRepository
     */
    protected $cameraRepository;

    /**
     * @var ViolationRepository
     */
    protected $violationRepository;

    /**
     * @var ConstantRepository
     */
    protected $constantRepository;

    /**
     * @var VoteRepository
     */
    protected $voteRepository;

    /**
     * @var BoxRecognitionRepository
     */
    protected $boxRecognitionRepository;

    /**
     * @var VideoAuthTypeRepository
     */
    protected $videoAuthTypeRepository;

    /**
     * CheckService constructor.
     */
    public function __construct()
    {
        $this->checkRepository = app(CheckRepository::class);
        $this->uikRepository = app(UikRepository::class);
        $this->cameraRepository = app(CameraRepository::class);
        $this->violationRepository = app(ViolationRepository::class);
        $this->constantRepository = app(ConstantRepository::class);
        $this->voteRepository = app(VoteRepository::class);
        $this->boxRecognitionRepository = app(BoxRecognitionRepository::class);
        $this->videoAuthTypeRepository = app(VideoAuthTypeRepository::class);
    }

    /**
     * Меняем переданные параметры проверки по ID
     *
     * @param $request
     * @return \App\Models\Check
     */
    public function updateCheck($request): \App\Models\Check
    {
        Check::where('check_id', $request['check_id'])->update($request);
        return $this->getCheck($request['check_id']);
    }

    /**
     * Создаем новую проверку
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \League\Csv\InvalidArgument
     */
    public function storeCheck($request): \Illuminate\Database\Eloquent\Collection
    {
        $check = new Check();
        $check->check_name = $request->name;
        $check->data_type_id = 4;
        $check->check_type_id = $request->check_type;
        $check->start_datetime = now()->format('Y-m-d H:i:s');
        $check->voting_date_start = $request->date_start;
        $check->voting_date_end = $request->date_end;
        $check->created_by = auth()->user()->user_id;
        $check->active = false;
        $check->save();

        /* Добавляем в таблицу storage_servers */
        $authLinks = json_decode($request->auth_link);
        $storageServerData = [];

        foreach ($authLinks as $link) {
            array_push($storageServerData, [
                'check_id' => $check->check_id,
                'auth_needed' => !!$request->auth_needed,
                'auth_type_id' => $request->auth_needed ? $request->auth_type_id : 0,
                'storage_link' => $link,
                'auth_login' => $request->auth_login,
                'auth_password' => $request->auth_password,
            ]);
        }

        StorageServer::insert($storageServerData);

        /* Добавляем в таблицу uiks */

        // load the CSV document from a file path
        $csv = Reader::createFromPath($request->csv, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

        foreach ($records as $record) {
            /* Заполняем таблицу uiks */
            $uik = Uik::create([
                'check_id' => $check->check_id,
                'region' => $record['region'],
                'region_number' => (int)$record['region_number'],
                'uik_number' => (int)$record['station_number'],
                'address' => $record['station_address'],
                'timezone_offset' => (int)$record['timezone_offset_minutes'],
                'voters_registered' => array_key_exists('voters_registered', $records) ? (int)$record['voters_registered'] : null,
                'tik' => array_key_exists('tik', $records) ? (int)$record['tik'] : null,
                'latitude' => $record['latitude'],
                'longitude' => $record['longitude'],
            ]);

            /* Заполняем таблицу cameras */
            $cameraRecords = explode(',', $record['camera_id']);

            foreach ($cameraRecords as $key => $item) {
                $item = str_replace('\'', '', $item);
                $item = str_replace('"', '', $item);
                $item = str_replace('[', '', $item);
                $item = str_replace(']', '', $item);

                Camera::insert([
                    'check_id' => $check->check_id,
                    'uik_id' => $uik->uik_id,
                    'cam_id' => $item,
                    'cam_num' => $key + 1,
                    'main' => null,
                    'skip_votes_summary' => null,
                ]);
            }
        }

        /* Добавляем в таблицу violation_decree */
        $violationDecree = new ViolationDecree();

        $violationDecree->check_id = $check->check_id;
        $violationDecree->decree = $request->decree ?? null;
        $violationDecree->save();

        Check::find($check->check_id)->update([
            'active' => true
        ]);

        return $this->getChecks();
    }

    /**
     * Получаем данные по проверке
     *
     * @param int $id
     *
     * @return \App\Models\Check
     */
    public function getCheck(int $id): ?\App\Models\Check
    {
        $check = $this->checkRepository->findCheck($id);

        if ($check) {
            $constant = $this->constantRepository->findConstant();

            $check->check_type = $check->check_type_id == 1 || $check->check_type_id == 2 ? 'оффлайн' : 'онлайн';

            $votineDateStart = date("d-m-Y", strtotime($check->voting_date_start));
            $votineDateEnd = date("d-m-Y", strtotime($check->voting_date_end));

            $check->period = "$votineDateStart - $votineDateEnd";
            $check->start_datetime = date("d-m-Y h:i:s", strtotime($check->start_datetime));
            $check->end_datetime = $check->end_datetime || $check->end_datetime !== 'none' ?
                date("d-m-Y h:i:s", strtotime($check->end_datetime)) :
                false;
            $check->constant = $constant;
        }

        return $check;
    }

    /**
     * Получаем данные по участкам относящиеся к проверке
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function getUiks(object $request): \Illuminate\Support\Collection
    {
        $id = $request->check;
        $uiks = $request->uiks ?? [];
        $regions = $request->regions ?? [];
        $votes = $request->votes ?? [];
        $votesOfficial = $request->votesOfficial ?? [];
        $votesPercent = $request->votesPercent ?? [];
        $boxTypes = $request->boxTypes ?? [];
        $processes = $request->processes ?? [];
        $violationTypes = $request->violationTypes ?? [];
        $violationStatuses = $request->violationStatuses ?? [];
        $others = $request->others ?? [];

        $collection = $this->uikRepository->findUiksByCheckId($id, $uiks, $regions, $votes, $votesOfficial);
        $constant = $this->constantRepository->findConstant();

        if (count($votesPercent)) {
            $collection = $collection->filter(function ($item) use ($votesPercent) {
                if (!$item->deviation_percent) return true;

                return WhereBetweenArrayHelper($votesPercent, ($item->deviation_percent * 100));
            })->values();
        }

        $violationTypesGroupByUiks = $this->cameraRepository->findUikViolationTypesByCheckId($id)->toArray();
        $cameraImagesGroupByUiks = $this->cameraRepository->findCameraImages()->toArray();
        $boxTypesGroupByUiks = $this->boxRecognitionRepository->findBoxTypesGroupByUiks()->toArray();
        $violationStatusesGroupByUiks = $this->cameraRepository->findUikViolationStatusesByCheckId($id)->toArray();

        $collection = $collection->map(function ($item) use (
            $violationTypesGroupByUiks,
            $cameraImagesGroupByUiks,
            $boxTypesGroupByUiks,
            $violationStatusesGroupByUiks,
            $constant
        ) {
            $uik_id = $item->uik_id;
            $keyViolationTypeExist = array_key_exists($uik_id, $violationTypesGroupByUiks);
            $keyImageExist = array_key_exists($uik_id, $cameraImagesGroupByUiks);
            $keyBoxTypeExist = array_key_exists($uik_id, $boxTypesGroupByUiks);
            $keyViolationStatusExist = array_key_exists($uik_id, $violationStatusesGroupByUiks);

            return collect($item)
                ->put('violation_types', $keyViolationTypeExist ? $violationTypesGroupByUiks[$uik_id]['violation_types'] : [])
                ->put('violation_statuses', $keyViolationStatusExist ? $violationStatusesGroupByUiks[$uik_id]['violation_statuses'] : [])
                ->put('images', $keyImageExist ? $cameraImagesGroupByUiks[$uik_id] : [])
                ->put('box_types', $keyBoxTypeExist ? $boxTypesGroupByUiks[$uik_id]['box_types'] : [])
                ->put('constant_revisor_error', $constant->revisor_error)
                ->put('votes_cameras_main_true_count', $item->votes_cameras_main_true_count ?? 0)
                ->put(
                    'votes_cameras_main_true_count_with_revisor_error',
                    $item->votes_cameras_main_true_count ?
                        $item->votes_cameras_main_true_count * (int)$constant->revisor_error :
                        0
                )
                ->put('deviation_error', (int)(abs($item->deviation) * $constant->revisor_error))
                ->put('official_turnout_percent', $item->votes_official_count ?
                    (int)(abs($item->deviation) / $item->votes_official_count * 100) :
                    0);
        });

        /* Фильтруем по типу ящиков */
        if (count($boxTypes)) {
            $collection = $collection->filter(function ($item) use ($boxTypes) {
                if (count($item['box_types'])) {

                    foreach ($item['box_types'] as $type) {
                        if (in_array($type['id'], $boxTypes)) {
                            return true;
                        }
                    }
                }

                return false;
            });
        }

        /* Филтруем по типу процесса (обработка) */
        if (count($processes)) {
            $collection = $collection->filter(function ($item) use ($processes) {
                foreach ($processes as $process) {
                    switch ($process) {
                        case 1:
                            return $item['videos_processing_count'];
                        case 2:
                            return $item['blacklist_count'];
                        case 3:
                            return $item['cameras_count'];
                        case 4:
                            return $item['uik_labels_5_count'];
                        case 5:
                            return $item['uik_labels_6_count'];
                        default:
                            return false;
                    }
                }
            });
        }

        /* Фильтруем по типу события */
        if (count($violationTypes)) {
            $collection = $collection->filter(function ($item) use ($violationTypes) {
                if (count($item['violation_statuses'])) {

                    foreach ($item['violation_statuses'] as $status) {
                        if (in_array($status['id'], $violationTypes)) {
                            return true;
                        }
                    }
                }

                return false;
            });
        }

        /* Фильтруем по статусу события */
        if (count($violationStatuses)) {
            $collection = $collection->filter(function ($item) use ($violationStatuses) {
                if (count($item['violation_statuses'])) {

                    foreach ($item['violation_statuses'] as $status) {
                        if (in_array($status['id'], $violationStatuses)) {
                            return true;
                        }
                    }
                }

                return false;
            });
        }

        /* Филтруем по прочим фильтрам */
        if (count($others)) {
            $collection = $collection->filter(function ($item) use ($others) {
                foreach ($others as $other) {
                    switch ($other) {
                        case 1:
                            return $item['votes_cameras_main_true_count'];
                        case 2:
                            return $item['votes_cameras_main_false_count'];
                        case 3:
                            return $item['votes_cameras_main_null_count'];
                        case 4:
                            return $item['real_votes_count'];
                        default:
                            return false;
                    }
                }
            });
        }

        return $collection->values();
    }

    /**
     *  Получаем список параметров для фильтрации
     * @param object $request
     * @return object
     */
    public function getFilterList(object $request): object
    {
        $this->uiks = $this->getUiks($request);

        return (object)[
            'uikList' => $this->getUikList(),
            'regionList' => $this->getRegionList(),
            'boxTypeList' => $this->getBoxTypeList(),
            'violationTypeList' => $this->getViolationTypeList(),
            'violationStatusList' => $this->getViolationStatusList(),
        ];
    }

    /**
     * Удаление проверки
     *
     * @param object $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function deleteCheck(object $request): \Illuminate\Database\Eloquent\Collection
    {
        $id = $request->check_id;

        Check::destroy($id);
        CheckUser::where('check_id', $id)->delete();
        StorageServer::where('check_id', $id)->delete();

        $uikIds = Uik::where('check_id', $id)->pluck('uik_id');
        Uik::whereIn('uik_id', $uikIds)->delete();
        UikLabel::whereIn('uik_id', $uikIds)->delete();

        $camNumericIds = Camera::where('check_id', $id)->pluck('cam_numeric_id');
        $violationIds = Violation::whereIn('cam_numeric_id', $camNumericIds)->pluck('violation_id');

        Camera::where('check_id', $id)->delete();
        Violation::whereIn('cam_numeric_id', $camNumericIds)->delete();
        Image::whereIn('cam_numeric_id', $camNumericIds)->delete();
        ViolationImage::whereIn('violation_id', $violationIds)->delete();
        ViolationComment::whereIn('violation_id', $violationIds)->delete();
        ViolationStatusChange::whereIn('violation_id', $violationIds)->delete();

        $textlIds = ViolationProtocol::whereIn('violation_id', $violationIds)
            ->where('text_type', 'custom')
            ->pluck('text_id');

        ViolationProtocol::whereIn('violation_id', $violationIds)->delete();
        ViolationsCustomText::whereIn('text_id', $textlIds)->delete();

        $recognitionIds = BoxRecognition::whereIn('uik_id', $uikIds)->pluck('recognition_id');

        BoxRecognition::whereIn('uik_id', $uikIds)->delete();
        UikTiming::whereIn('uik_id', $uikIds)->delete();
        Vote::whereIn('uik_id', $uikIds)->delete();
        VotesOfficial::whereIn('uik_id', $uikIds)->delete();
        IntermediateVotesOfficial::whereIn('uik_id', $uikIds)->delete();

        BoxesInfo::whereIn('recognition_id', $recognitionIds)->delete();

        $imageIds = BoxRecognitionsImage::whereIn('recognition_id', $recognitionIds)->pluck('image_id');
        Image::destroy($imageIds);

        BoxRecognitionsImage::whereIn('recognition_id', $recognitionIds)->delete();

        $videoIds = Video::where('check_id', $id)->pluck('video_id');
        Video::where('check_id', $id)->delete();
        VideosToSelectCam::whereIn('video_id', $videoIds)->delete();
        VideosToFindBox::whereIn('video_id', $videoIds)->delete();
        VideosToCount::whereIn('video_id', $videoIds)->delete();
        VideosProcessing::whereIn('video_id', $videoIds)->delete();
        VideosErrored::where('check_id', $id)->delete();

        ViolationDecree::where('check_id', $id)->delete();
        ViolationAutoText::where('check_id', $id)->delete();
        VoteSum::whereIn('uik_id', $uikIds)->delete();
        VotesDeviation::whereIn('uik_id', $uikIds)->delete();
        VideosToRealCount::where('check_id', $id)->delete();

        return $this->getChecks();
    }

    /**
     * Получаем перечень событий относящихся к проверке
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function getViolationCams(object $request): \Illuminate\Support\Collection
    {
        $id = $request->check;
        $statuses = $request->statuses ?? [];

        $violations = $this->violationRepository->findViolationCams($id, $statuses);

        return collect([
            'violations' => $violations,
            'violationsGroupByName' => $violations->groupBy('status_name')->map(function ($item) {
                if (!count($item)) return $item;

                return collect([
                    'name' => $item[0]->status_name,
                    'count' => count($item),
                ]);
            })->values()
        ]);
    }

    /**
     * Получаем список УИКов для фильтрации
     * @return \Illuminate\Support\Collection
     */
    private function getUikList(): \Illuminate\Support\Collection
    {
        return $this->uiks->map(function ($item) {
            return (object)[
                'id' => $item['uik_id'],
                'name' => $item['region'] . ', УИК №' . $item['uik_number'],
            ];
        });
    }

    /**
     * Получаем список Регионов для фильтрации
     * @return \Illuminate\Support\Collection
     */
    private function getRegionList(): \Illuminate\Support\Collection
    {
        return $this->uiks
            ->groupBy('region_number')
            ->map(function ($item) {
                return (object)[
                    'id' => $item[0]['region_number'],
                    'name' => $item[0]['region'],
                ];
            })->values();
    }

    /**
     * Получаем список типов ящиков
     * @return \Illuminate\Support\Collection
     */
    private function getBoxTypeList(): \Illuminate\Support\Collection
    {
        return $this->uiks
            ->reduce(function ($carry, $item) {
                $boxTypes = $item['box_types'];
                if (count($boxTypes)) {
                    foreach ($boxTypes as $type) {
                        if (!$carry->contains('id', $type['id'])) {
                            $carry->push([
                                'id' => $type['id'],
                                'name' => $type['name']
                            ]);
                        }
                    }
                }

                return $carry;
            }, collect());
    }

    /**
     * Получаем список типов событий
     * @return \Illuminate\Support\Collection
     */
    private function getViolationTypeList(): \Illuminate\Support\Collection
    {
        return $this->uiks
            ->reduce(function ($carry, $item) {
                $types = $item['violation_types'];

                if (count($types)) {
                    foreach ($types as $type) {
                        if ($type['id'] && !$carry->contains('id', $type['id'])) {
                            $carry->push([
                                'id' => $type['id'],
                                'name' => $type['name']
                            ]);
                        }
                    }
                }

                return $carry;
            }, collect());
    }

    /**
     * Получаем список статусов событий
     * @return \Illuminate\Support\Collection
     */
    private function getViolationStatusList(): \Illuminate\Support\Collection
    {
        return $this->uiks
            ->reduce(function ($carry, $item) {
                $statuses = $item['violation_statuses'];

                if (count($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status['id'] && !$carry->contains('id', $status['id'])) {
                            $carry->push([
                                'id' => $status['id'],
                                'name' => $status['name']
                            ]);
                        }
                    }
                }

                return $carry;
            }, collect());
    }

    /**
     * Подгружаем на сервер участки из CSV-файла
     * @param object $request
     * @return void
     * @throws \League\Csv\Exception
     */
    public function uploadUiksByCsv(object $request): void
    {
        // load the CSV document from a file path
        $csv = Reader::createFromPath($request->file, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

        foreach ($records as $record) {
            /* Заполняем таблицу uiks */
            $uik = Uik::create([
                'check_id' => $request->check_id,
                'region' => $record['region'],
                'region_number' => (int)$record['region_number'],
                'uik_number' => (int)$record['station_number'],
                'address' => $record['station_address'],
                'timezone_offset' => (int)$record['timezone_offset_minutes'],
                'voters_registered' => array_key_exists('voters_registered', $records) ? (int)$record['voters_registered'] : null,
                'tik' => array_key_exists('tik', $records) ? (int)$record['tik'] : null,
                'latitude' => $record['latitude'],
                'longitude' => $record['longitude'],
            ]);

            /* Заполняем таблицу cameras */
            $cameraRecords = explode(',', $record['camera_id']);

            foreach ($cameraRecords as $key => $item) {
                $item = str_replace('\'', '', $item);
                $item = str_replace('"', '', $item);
                $item = str_replace('[', '', $item);
                $item = str_replace(']', '', $item);
                $item = explode(';', $item);

                Camera::insert([
                    'check_id' => $request->check_id,
                    'uik_id' => $uik->uik_id,
                    'cam_id' => $item[0],
                    'cam_num' => $key + 1,
                    'main' => null,
                    'skip_votes_summary' => null,
                ]);
            }
        }
    }

    /**
     *  Отправляем на проверку участки
     * @param object $request
     */
    public function reviewUik(object $request): void
    {
        $check_id = $request->check_id;
        $uik_ids = $request->uiks;
        $videos = $this->cameraRepository->findVideosByUikId($uik_ids);
        $camNumericIds = $videos->pluck('cam_numeric_ids')->reduce(function ($carry, $item) {
            return $carry->merge($item);
        }, collect());

        $videosToCheck = VideosToCheck::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');
        $checkingVideo = CheckingVideo::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');
        $checkedVideo = CheckedVideo::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');

        /* Оставляем только те видео, которых нет в вышеуказанных таблицах */
        $camNumericIds = $camNumericIds->diff($videosToCheck)->diff($checkingVideo)->diff($checkedVideo)->values();

        $videos->each(function ($item) use ($check_id, $camNumericIds) {
            $arr = array_intersect($item['cam_numeric_ids'], $camNumericIds->toArray());
            if (count($arr)) {
                VideosToCheck::create([
                    'video_id' => $item['video_id'],
                    'check_id' => $check_id,
                    'cam_numeric_id' => $item['cam_numeric_ids'][0]
                ]);
            }
        });
    }

    /**
     * Загружаем официальную явку
     * @param object $request
     * @throws \League\Csv\Exception
     */
    public function uploadOfficialVote(object $request): void
    {
        $owner = auth()->user();

        // load the CSV document from a file path
        $csv = Reader::createFromPath($request->file, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

        foreach ($records as $record) {
            $uik = Uik::where('region_number', $record['region_number'])
                ->where('uik_number', $record['station_number'])
                ->first();

            if ($uik) {
                $voteOfficial = VotesOfficial::where('uik_id', $uik->uik_id)->first();
            }

            if ($uik && !$voteOfficial) {
                VotesOfficial::create([
                    'uik_id' => $uik->uik_id,
                    'voters_turnout' => $record['voters_turnout'],
                    'voters_voted_at_station' => $record['voters_voted_at_station'],
                    'voters_voted_early' => $record['voters_voted_early'],
                    'voters_voted_outside_station' => $record['voters_voted_outside_station'],
                    'ballots_in_ballot_boxes_after_election' => $record['ballots_in_ballot_boxes_after_election'],
                    'candidates_votes_json' => $record['candidates_votes_json'],
                    'ballots_valid' => $record['ballots_valid'],
                    'ballots_invalid' => $record['ballots_invalid'],
                    'loaded_by' => $owner->user_id
                ]);
            };
        }
    }

    /**
     *  Сравниваем явку Ревизора с официальной
     * @param object $request
     * @return void
     */
    public function compareTurnout(object $request): void
    {
        $checkId = $request->check_id;
        $checkDateStart = $request->voting_date_start;
        $checkDateEnd = $request->voting_date_end;

        $votesUiksAndDate = $this->voteRepository->findVoteGroupByUiksAndDateByCheckId($checkId);
        foreach ($votesUiksAndDate as $vote) {
            VoteSum::updateOrCreate(
                [
                    'uik_id' => $vote->uik_id,
                    'date' => $vote->vote_datetime,
                ],
                [
                    'voters_turnout' => $vote->voters_turnout
                ]
            );
        }

        $votesByUiks = $this->voteRepository->findVoteGroupByUiksByCheckId($checkId);
        foreach ($votesByUiks as $vote) {

            $diff = $vote->revisor_votes - $vote->official_votes;

            VotesDeviation::create([
                'uik_id' => $vote->uik_id,
                'date_start' => $checkDateStart,
                'date_end' => $checkDateEnd,
                'deviation' => $diff
            ]);

            $constant = $this->constantRepository->findConstant();

            if ($vote->official_votes && (abs($diff) / $vote->official_votes) > $constant->revisor_error) {
                foreach ($vote->cameras as $camera) {
                    if ($camera->cam_numeric_id) {

                        Violation::firstOrCreate(
                            [
                                'cam_numeric_id' => $camera->cam_numeric_id,
                                'violation_type_id' => 7,
                            ],
                            [
                                'creation_datetime' => now()->format('Y-m-d H:i:s'),
                                'violation_datetime_start' => $checkDateStart,
                                'violation_datetime_end' => $checkDateEnd,
                                'status_id' => 1,
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * Загружаем промежуточную явку
     * @param object $request
     * @throws \League\Csv\Exception
     */
    public function uploadIntermediateVote(object $request): void
    {
        $owner = auth()->user();

        // load the CSV document from a file path
        $csv = Reader::createFromPath($request->file, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = iterator_to_array($csv->getRecords()); //returns all the CSV records as an Iterator object

        foreach ($records as $record) {
            $uik = Uik::where('region_number', $record['region_number'])
                ->where('uik_number', $record['station_number'])
                ->first();

            if ($uik) {
                IntermediateVotesOfficial::firstOrCreate(
                    [
                        'uik_id' => $uik->uik_id
                    ],
                    [
                        'date' => $record['date'],
                        'voters_voted_in_ballot_box' => $record['voters_voted_in_ballot_box'],
                        'loaded_by' => $owner->user_id
                    ]
                );
            }
        }
    }

    /**
     * Сравниваем промежуточная явку Ревизора с официальной
     * @param object $request
     * @return void
     */
    public function compareIntermediateTurnout(object $request): void
    {
        $checkId = $request->check_id;

        $votesUiksAndDate = $this->voteRepository->findVoteGroupByUiksAndDateByCheckId($checkId);
        foreach ($votesUiksAndDate as $vote) {
            VoteSum::updateOrCreate(
                [
                    'uik_id' => $vote->uik_id,
                    'date' => $vote->vote_datetime,
                ],
                [
                    'voters_turnout' => $vote->voters_turnout
                ]
            );
        }

        $votesByUiks = $this->voteRepository->findVoteIntermediateGroupByUiksByCheckId(1);

        foreach ($votesByUiks as $vote) {

            $diff = $vote->revisor_votes - $vote->intermediate_votes;

            VotesDeviation::create([
                'uik_id' => $vote->uik_id,
                'date_start' => $vote->date,
                'date_end' => $vote->date,
                'deviation' => $diff
            ]);

            $constant = $this->constantRepository->findConstant();
            if ((abs($diff) / $vote->intermediate_votes) > $constant->intermediate_votes) {
                foreach ($vote->cameras as $camera) {
                    if ($camera->cam_numeric_id) {

                        Violation::firstOrCreate(
                            [
                                'cam_numeric_id' => $camera->cam_numeric_id,
                                'violation_type_id' => 6,
                            ],
                            [
                                'creation_datetime' => now()->format('Y-m-d H:i:s'),
                                'violation_datetime_start' => $vote->date,
                                'violation_datetime_end' => $vote->date,
                                'status_id' => 1,
                            ]
                        );
                    }
                }
            }
        }
    }

    /**
     * Отправляем участки на ручной подсчет явки
     * @param object $request
     */
    public function countingUik(object $request): void
    {
        $check_id = $request->check_id;
        $uik_ids = $request->uiks;
        $videos = $this->cameraRepository->findVideosByUikId($uik_ids);
        $camNumericIds = $videos->pluck('cam_numeric_ids')->reduce(function ($carry, $item) {
            return $carry->merge($item);
        }, collect());

        $videosToCheck = VideosToRealCount::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');
        $checkingVideo = RealCountingVideo::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');
        $checkedVideo = RealCountedVideo::whereIn('cam_numeric_id', $camNumericIds)
            ->select('cam_numeric_id')
            ->get()
            ->pluck('cam_numeric_id');

        /* Оставляем только те видео, которых нет в вышеуказанных таблицах */
        $camNumericIds = $camNumericIds->diff($videosToCheck)->diff($checkingVideo)->diff($checkedVideo)->values();

        $videos->each(function ($item) use ($check_id, $camNumericIds) {
            $arr = array_intersect($item['cam_numeric_ids'], $camNumericIds->toArray());
            if (count($arr)) {
                VideosToRealCount::create([
                    'video_id' => $item['video_id'],
                    'check_id' => $check_id,
                    'cam_numeric_id' => $item['cam_numeric_ids'][0]
                ]);
            }
        });
    }

    /**
     * Получаем проверки
     *
     * @return \\Illuminate\Database\Eloquent\Collection
     */
    public function getChecks(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->checkRepository->findChecks();
    }

    /**
     * Получаем список типов авторизации
     * @return \Illuminate\Support\Collection
     */
    public function getAuthTypeList(): \Illuminate\Support\Collection
    {
        return $this->videoAuthTypeRepository->findTypes();
    }

    /**
     * Получаем нарушения для модалок
     * @param int $request
     * @return \Illuminate\Support\Collection
     */
    public function getViolationForEventTable(object $request)
    {
        $check = $request->check;
        $statuses = $request->statuses ?? [];

        return $this->violationRepository->findViolationForEventTable($check, $statuses);
    }
}
