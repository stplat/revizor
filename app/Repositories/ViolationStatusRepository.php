<?php

namespace App\Repositories;


use App\Models\ViolationStatus as Model;

/**
 * Class VideosProcessingRepository
 * @package App\Repositories
 */
class ViolationStatusRepository extends BaseRepository
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
     * @return \App\Models\Violation|null
     */
    public function findViolationStatusList(): \Illuminate\Support\Collection
    {
        $columns = [
            'status_id as id',
            'status_name as name',
        ];

        return $this->startCondition()
            ->select($columns)
            ->get();
    }
}
