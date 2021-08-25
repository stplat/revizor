<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Uik;
use App\Models\VotesOfficial;

class VotesOfficialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Uik::all()->each(function ($item) {
            $officialVote = new VotesOfficial();
            $officialVote->uik_id = $item->uik_id;
            $officialVote->ballots_in_ballot_boxes_after_election = rand(100, 2500);
            $officialVote->save();
        });
    }
}
