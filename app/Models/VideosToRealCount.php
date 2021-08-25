<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideosToRealCount extends Model
{
  public $table = 'videos_to_real_count';
  public $timestamps = false;

    protected $fillable = [
        'video_id',
        'check_id',
        'cam_numeric_id'
    ];
}
