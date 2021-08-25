<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntermediateVotesOfficial extends Model
{
    public $table = 'intermediate_votes_official';
    public $timestamps = false;

    protected $fillable = [
        'uik_id',
        'date',
        'voters_voted_in_ballot_box',
        'loaded_by'
    ];
}
