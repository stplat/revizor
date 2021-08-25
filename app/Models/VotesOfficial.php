<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotesOfficial extends Model
{
    public $table = 'votes_official';
    public $timestamps = false;

    protected $fillable = [
        'uik_id',
        'voters_turnout',
        'voters_voted_at_station',
        'voters_voted_early',
        'voters_voted_outside_station',
        'ballots_in_ballot_boxes_after_election',
        'candidates_votes_json',
        'ballots_valid',
        'ballots_invalid',
        'loaded_by'
    ];

    public function uik()
    {
        return $this->hasMany(Uik::class, 'uik_id', 'uik_id');
    }
}
