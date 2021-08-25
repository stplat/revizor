<?php

namespace App\Services;

use App\Models\ViolationComment;
use App\Repositories\ViolationCommentRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class ViolationCommentService
 * @package App\Services
 */
class ViolationCommentService
{
    /**
     * @var ViolationCommentRepository
     */
    protected $violationCommentRepository;

    /**
     * ViolationCommentService constructor.
     */
    public function __construct()
    {
        $this->violationCommentRepository = app(ViolationCommentRepository::class);
    }

    /**
     * Получаем комментарии
     * @param int $violationId
     * @return \Illuminate\Support\Collection;
     */
    public function getComments(int $violationId): \Illuminate\Support\Collection
    {
        return $this->violationCommentRepository->findComments($violationId);
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

        return $this->getComments($request->violation);
    }
}
