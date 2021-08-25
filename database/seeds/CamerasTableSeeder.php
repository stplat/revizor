<?php

use Illuminate\Database\Seeder;
use App\Models\Camera;
use Illuminate\Support\Facades\DB;

class CamerasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/cameras.sql'));

        // $data = [];
        // $mains = [true, false, null];

        // for ($i = 0; $i < 200; $i++) {
        //     array_push($data, [
        //         'cam_numeric_id' => rand(1, 20),
        //         'check_id' => rand(1, 4),
        //         'uik_id' => rand(1, 40),
        //         'cam_id' => uniqid(),
        //         'cam_num' => rand(1, 5),
        //         'main' => $mains[rand(0, 2)],
        //         'skip_votes_summary' => $mains[rand(0, 1)],
        //     ]);
        // }

        // Camera::insert($data);
        // Camera::insert([
        //     [
        //         'cam_numeric_id' => 1,
        //         'check_id' => 1,
        //         'uik_id' => 1,
        //         'cam_id' => 'sdasdg12-dasvc124',
        //         'cam_num' => 1,
        //         'main' => true,
        //         'skip_votes_summary' => false,
        //     ],
        //     [
        //         'cam_numeric_id' => 2,
        //         'check_id' => 1,
        //         'uik_id' => 1,
        //         'cam_id' => 'gsdv3qs3-hsdv4jh4',
        //         'cam_num' => 2,
        //         'main' => false,
        //         'skip_votes_summary' => false,
        //     ],
        //     [
        //         'cam_numeric_id' => 3,
        //         'check_id' => 1,
        //         'uik_id' => 2,
        //         'cam_id' => 'cvxa9fdx-xcz1ska2',
        //         'cam_num' => 1,
        //         'main' => null,
        //         'skip_votes_summary' => null,
        //     ],
        //     [
        //         'cam_numeric_id' => 4,
        //         'check_id' => 1,
        //         'uik_id' => 1,
        //         'cam_id' => '9jjgfhdd-ntuhhtet',
        //         'cam_num' => 2,
        //         'main' => true,
        //         'skip_votes_summary' => null,
        //     ]
        // ]);
    }
}
