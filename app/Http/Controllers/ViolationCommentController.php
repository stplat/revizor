<?php

namespace App\Http\Controllers;

use App\Services\ViolationCommentService;

use App\Http\Requests\ViolationComment\ViolationCommentShow;
use App\Http\Requests\ViolationComment\ViolationCommentStore;

class ViolationCommentController extends Controller
{
    /**
     * @var ViolationCommentService
     */
    protected $violationCommentService;

    public function __construct(ViolationCommentService $violationCommentService)
    {
        $this->violationCommentService = $violationCommentService;
    }

    /**
     * Получаем комментарии (или один комментарий)
     *
     * @param ViolationCommentShow $request
     * @return \Illuminate\Support\Collection
     */
    public function show(ViolationCommentShow $request)
    {
        return $this->violationCommentService->getComments($request->input('violation'));
    }

    /**
     * Добавляем комментарий в базу
     *
     * @param ViolationCommentStore $request
     * @return \Illuminate\Support\Collection
     */
    public function store(ViolationCommentStore $request)
    {
        return $this->violationCommentService->storeComment($request);
    }
}
