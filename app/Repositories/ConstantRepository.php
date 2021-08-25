<?php

namespace App\Repositories;

use App\Models\Constant as Model;

/**
 * Class ConstantRepository
 * @package App\Repositories
 */
class ConstantRepository extends BaseRepository
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
     * @return Model
     */
    public function findConstant(): Model
    {
        return $this->startCondition()->first();
    }
}
