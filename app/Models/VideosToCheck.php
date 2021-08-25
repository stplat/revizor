<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideosToCheck extends Model
{
    public $table = 'videos_to_check';
    public $timestamps = false;

    protected $fillable = [
        'video_id',
        'check_id',
        'cam_numeric_id'
    ];
}
