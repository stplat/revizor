<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxRecognition extends Model
{
    public $table = 'box_recognitions';
    public $primaryKey = 'recognition_id';
    public $timestamps = false;
}
