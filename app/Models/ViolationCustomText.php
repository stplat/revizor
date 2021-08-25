<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationCustomText extends Model
{
    public $table = 'violations_custom_text';
    public $primaryKey = 'custom_text_id';
    public $timestamps = false;
}
