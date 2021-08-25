<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationStatusChange;

class ViolationStatusChangesTableSeeder extends Seeder
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

        for ($i = 0; $i < 5; $i++) {
            array_push($data, [
                'changed_by' => rand(1, 4),
                'status_id' => rand(1, 7),
            ]);
        }

        ViolationStatusChange::insert($data);
    }
}
