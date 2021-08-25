<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\VideosAuthType as Model;


/**
 * Class VideoAuthTypeRepository
 * @package App\Repositories
 */
class VideoAuthTypeRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем список типов авторизации
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findTypes(): \Illuminate\Database\Eloquent\Collection
    {
        $columns = [
            'auth_type_id as id',
            'type_name as name'
        ];

        return $this->startCondition()
            ->select($columns)
            ->get();
    }
}
