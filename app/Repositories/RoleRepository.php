<?php

namespace App\Repositories;

use App\Models\Role as Model;

/**
 * Class RoleRepository
 * @package App\Repositories
 */
class RoleRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем перечень констант
     * @return \Illuminate\Support\Collection|null
     */
    public function findRoles(): ?\Illuminate\Database\Eloquent\Collection
    {
        $columns = [
            'role_id as id',
            'role_name as name',
        ];

        return $this->startCondition()
            ->select($columns)
            ->get();
    }
}
