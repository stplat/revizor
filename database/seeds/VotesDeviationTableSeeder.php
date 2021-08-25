<?php

use Illuminate\Database\Seeder;
use App\Models\VotesDeviation;

class VotesDeviationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 100; $i++) {
            array_push($data, [
                'uik_id' => rand(1, 10),
                'date_start' => RandomDateHelper(),
                'deviation' => rand(20, 50) / rand(90, 100),
            ]);
        }

        VotesDeviation::insert($data);
    }
}
