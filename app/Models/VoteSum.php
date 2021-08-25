<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteSum extends Model
{
    public $table = 'votes_sum';
    public $timestamps = false;

    protected $fillable = [
      'uik_id',
      'date',
      'voters_turnout'
    ];
}
