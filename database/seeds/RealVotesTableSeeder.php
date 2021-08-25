<?php

use Illuminate\Database\Seeder;
use App\Models\RealVote;

class RealVotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //    DB::unprepared(file_get_contents(__DIR__ . './../dumps/votes.sql'));
        $data = [];

        for ($i = 0; $i < 5000; $i++) {
            array_push($data, [
                'uik_id' => rand(1, 8),
                'vote_datetime' => RandomDateHelper(),
                'video_id' => rand(1, 50),
                'user_id' => rand(1, 3),
            ]);
        }

        RealVote::insert($data);
    }
}
