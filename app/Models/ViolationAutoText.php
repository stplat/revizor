<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationAutoText extends Model
{
    public $table = 'violation_auto_text';
    public $primaryKey = 'auto_text_id';
    public $timestamps = false;
}
