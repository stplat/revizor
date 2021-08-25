<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideosProcessing extends Model
{
    public $table = 'videos_processing';
    public $primaryKey = 'processing_id';
    public $timestamps = false;
}
