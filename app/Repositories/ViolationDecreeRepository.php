<?php

namespace App\Repositories;

use App\Models\ViolationDecree as Model;

/**
 * Class ViolationDecreeRepository
 * @package App\Repositories
 */
class ViolationDecreeRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем список протоколов по проверке
     * @param int $checkId
     */
    public function findViolationDecreeList(int $checkId): \Illuminate\Support\Collection
    {
        $columns = [
            'decree_id as id',
            'decree as name',
        ];

        return $this->startCondition()
            ->select($columns)
            ->where('check_id', $checkId)
            ->get();
    }
}
