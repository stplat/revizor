<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modal\ModalShow;

use App\Http\Requests\Modal\ModalShowVideo;
use App\Http\Requests\Modal\ModalStoreClaim;
use App\Http\Requests\Modal\ModalUpdateViolation;
use App\Http\Requests\Modal\ModalUploadClaimResponse;
use App\Http\Requests\Modal\ModalDestroyClaim;
use App\Http\Requests\Modal\ModalCommentStore;
use App\Http\Requests\Modal\ModalShowViolationList;

use App\Services\ModalService;

class ModalController extends Controller
{
    /**
     * @var ModalService
     */
    protected $modalService;

    public function __construct(ModalService $modalService)
    {
        $this->modalService = $modalService;
    }

    /**
     * Получаем список необходимых параметров для отображения модалки
     *
     * @param ModalShow $request
     * @return \Illuminate\Support\Collection
     */
    public function show(ModalShow $request)
    {
        return $this->modalService->getInitialData($request);
    }

    /**
     * Меняем статус нарушения
     *
     * @param ModalUpdateViolation $request
     * @return object
     */
    public function updateViolation(ModalUpdateViolation $request)
    {
        if (array_key_exists('blocked', $request->toArray())) {
            return $this->modalService->updateViolationBlocked($request);
        }
        return $this->modalService->updateViolationStatus($request);
    }

    /**
     * Генерируем жалобу
     *
     * @param ModalStoreClaim $request
     * @return \Illuminate\Support\Collection
     */
    public function storeClaim(ModalStoreClaim $request): \Illuminate\Support\Collection
    {
        return $this->modalService->storeClaim($request);
    }

    /**
     * Загружаем ответ по жалобе
     *
     * @param ModalUploadClaimResponse $request
     * @return \Illuminate\Support\Collection
     */
    public function uploadClaimResponse(ModalUploadClaimResponse $request)
    {
        return $this->modalService->uploadClaimResponse($request);
    }

    /**
     * Удаляем жалобу и меняем статус события
     *
     * @param ModalDestroyClaim $request
     * @return object
     */
    public function destroyClaim(ModalDestroyClaim $request): object
    {
        return $this->modalService->destroyClaim($request->input('violation'));
    }

    /**
     * Добавляем комментарий в базу
     *
     * @param ModalCommentStore $request
     * @return \Illuminate\Support\Collection
     */
    public function storeComment(ModalCommentStore $request)
    {
        return $this->modalService->storeComment($request);
    }

    /**
     * Получаем ссылку на видео и прочие параметры
     *
     * @param ModalShowVideo $request
     * @return ?\App\Models\Video
     */
    public function showVideo(ModalShowVideo $request)
    {
        return $this->modalService->getVideo($request);
    }

    /**
     * Получаем ссылку на видео и прочие параметры
     *
     * @param ModalShowViolationList $request
     * @return ?\App\Models\Video
     */
    public function showViolationList(ModalShowViolationList $request)
    {
        return $this->modalService->getViolationListWithNoVideo($request);
    }
}
