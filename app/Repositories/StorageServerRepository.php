<?php

namespace App\Repositories;

use App\Models\StorageServer as Model;

/**
 * Class StorageServerRepository
 * @package App\Repositories
 */
class StorageServerRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}
