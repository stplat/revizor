<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class VideosProcessingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/videos_processing.sql'));

        // $data = [];
        // $taskTypes = ['selecting_cam', 'boxes', 'counting'];
        // $processeds = [true, false];

        // for ($i = 0; $i < 100; $i++) {
        //     array_push($data, [
        //         'check_id' => rand(1, 4),
        //         'uik_id' => $i + 1,
        //         'cam_numeric_id' => $i + 1,
        //         'task_type' => $taskTypes[rand(0, 2)],
        //         'video_id' => $i + 1,
        //         'server_id' => rand(1, 50),
        //         'processed' => rand(0, 1),
        //     ]);
        // }

        // VideosProcessing::insert($data);
    }
}
