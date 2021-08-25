<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationDecree extends Model
{
    public $table = 'violation_decree';
    public $primaryKey = 'decree_id';
    public $timestamps = false;
}
