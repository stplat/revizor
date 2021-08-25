<?php

namespace App\Repositories;

use App\Models\ViolationComment as Model;

use Jenssegers\Date\Date;

/**
 * Class ViolationCommentRepository
 * @package App\Repositories
 */
class ViolationCommentRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем комменатрии
     * @param int $violationId
     * @return \Illuminate\Support\Collection
     */
    public function findComments(int $violationId): \Illuminate\Support\Collection
    {
        $columns = [
            'violation_comments.datetime as date',
            'violation_comments.comment',
            'users.username',
        ];

        return $this->startCondition()
            ->select($columns)
            ->where('violation_id', $violationId)
            ->leftJoin('users', 'violation_comments.user_id', '=', 'users.user_id')
            ->orderBy('datetime', 'DESC')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'date' => Date::parse($item->date)->format('j F Yг.'),
                    'time' => $item->date,
                    'comment' => $item->comment,
                    'username' => $item->username,
                ];
            });
    }
}
