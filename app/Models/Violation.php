<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    public $primaryKey = 'violation_id';
    public $timestamps = false;

    protected $fillable = [
        'cam_numeric_id',
        'violation_type_id',
        'creation_datetime',
        'violation_datetime_start',
        'violation_datetime_end',
        'status_id',
    ];

    public function cameras(){
        return $this->hasMany(Camera::class, 'cam_numeric_id', 'cam_numeric_id');
    }
}
