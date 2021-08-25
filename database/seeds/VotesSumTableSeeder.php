<?php

use Illuminate\Database\Seeder;
use App\Models\VoteSum;

class VotesSumTableSeeder extends Seeder
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

        for ($i = 0; $i < 200; $i++) {
            array_push($data, [
                'uik_id' => rand(1, 50),
                'date' => RandomDateHelper(),
                'voters_turnout' => rand(100, 500),
            ]);
        }

        VoteSum::insert($data);
    }
}
