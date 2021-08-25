<?php

use Illuminate\Database\Seeder;
use App\Models\Violation;

use Illuminate\Support\Facades\DB;

class ViolationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/violations.sql'));
        // $data = [];
        // $date = [RandomDateHelper(), null];

        // for ($i = 0; $i < 1; $i++) {
        //     array_push($data, [
        //         'cam_numeric_id' => 1,
        //         'violation_type_id' => 4,
        //         'status_id' => 1,
        //         'creation_datetime' => RandomDateHelper(),
        //         'violation_datetime_start' => RandomDateHelper(),
        //         'violation_datetime_end' => RandomDateHelper(),
        //     ]);
        // }

        // Violation::insert($data);
    }
}
