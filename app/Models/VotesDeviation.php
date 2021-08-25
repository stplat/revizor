<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotesDeviation extends Model
{
    public $table = 'votes_deviation';
    public $timestamps = false;

    protected $fillable = [
        'uik_id',
        'date_start',
        'date_end',
        'deviation'
    ];
}
