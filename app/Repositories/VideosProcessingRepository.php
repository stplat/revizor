<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\VideosProcessing as Model;

/**
 * Class VideosProcessingRepository
 * @package App\Repositories
 */
class VideosProcessingRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function findVideoWithRecognitionCount() {

    }
}
