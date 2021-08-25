<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Uik;
use App\Models\IntermediateVotesOfficial;

class IntermediateVotesOfficialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [];

        // for ($i = 0; $i < 5000; $i++) {
        //     array_push($data, [
        //         'date' => RandomDateHelper(),
        //         'uik_id' => rand(1, 8),
        //         'voters_voted_in_ballot_box' => rand(1, 100),
        //         'loaded_by' => rand(1, 10),
        //     ]);
        // }

        // DB::table('intermediate_votes_official')->insert($data);

        Uik::all()->each(function ($item) {
            $vote = new IntermediateVotesOfficial();
            $vote->date = RandomDateHelper();
            $vote->uik_id = $item->uik_id;
            $vote->voters_voted_in_ballot_box = rand(1, 100);
            $vote->loaded_by = rand(1, 10);
            $vote->save();
        });

        Uik::all()->each(function ($item) {
            $vote = new IntermediateVotesOfficial();
            $vote->date = '2020-09-13 08:16:04';
            $vote->uik_id = $item->uik_id;
            $vote->voters_voted_in_ballot_box = rand(1, 100);
            $vote->loaded_by = rand(1, 10);
            $vote->save();
        });
    }
}
