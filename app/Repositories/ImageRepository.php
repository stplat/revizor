<?php

namespace App\Repositories;

use App\Models\Image as Model;


/**
 * Class ImageRepository
 * @package App\Repositories
 */
class ImageRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}
