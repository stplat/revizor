<?php

namespace App\Repositories;


use App\Models\ViolationStatusChange as Model;

/**
 * Class VideosProcessingRepository
 * @package App\Repositories
 */
class ViolationStatusChangeRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем данные по одному событию для подсказки (нарушению)
     * @param int $violationId
     * @return String
     */
    public function findLastCreator(int $violationId): ?String
    {
        $columns = [
            'username',
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->leftJoin('users', 'violation_status_changes.changed_by', '=', 'users.user_id')
            ->where('violation_id', $violationId)
            ->orderBy('status_datetime', 'DESC')
            ->first();

        return $model ? $model->username : null;
    }
}
