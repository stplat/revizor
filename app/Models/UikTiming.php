<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UikTiming extends Model
{
    protected $primaryKey = 'timing_id';
    public $timestamps = false;

    protected $fillable = [
        'approved_8h',
        'approved_10h',
        'approved_12h',
        'approved_14h',
        'approved_16h',
        'approved_18h',
    ];
}
