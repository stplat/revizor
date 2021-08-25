<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $primaryKey = 'cam_numeric_id';
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany(Image::class, 'cam_numeric_id', 'cam_numeric_id');
    }

    public function blacklist()
    {
        return $this->hasMany(CameraBlacklist::class, 'cam_numeric_id', 'cam_numeric_id');
    }
}
