<?php

use Illuminate\Database\Seeder;
use App\Models\Vote;

class VotesTableSeeder extends Seeder
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
                'vote_conf' => rand(1, 100),
                'video_id' => rand(1, 50),
            ]);
        }

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-12 10:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-11 18:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-12 17:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-12 01:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-12 01:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        // array_push($data, [
        //     'uik_id' => 1,
        //     'vote_datetime' => '2021-05-11 05:27:31',
        //     'vote_conf' => rand(100, 500),
        //     'video_id' => rand(1, 50),
        // ]);

        Vote::insert($data);
    }
}
