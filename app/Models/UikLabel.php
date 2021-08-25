<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UikLabel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uik_id',
        'label_id',
    ];
}
