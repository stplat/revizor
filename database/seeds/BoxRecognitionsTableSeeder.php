<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class BoxRecognitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/box_recognitions.sql'));
        // $data = [];

        // for ($i = 0; $i < 100; $i++) {
        //     array_push($data, [
        //         'uik_id' => rand(1, 20),
        //         'cam_numeric_id' => rand(1, 10),
        //         'video_id' => $i + 1,
        //         'checked' => rand(0, 1),
        //         'checking' => rand(0, 1),
        //         'checked_by' => rand(1, 4),
        //         'boxes_num' => rand(1, 10),
        //         'check_datetime' => '2018-09-16 10:25:03.95478',
        //     ]);
        // }

        // BoxRecognition::insert($data);
    }
}
