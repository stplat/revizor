<?php

use Illuminate\Database\Seeder;
use App\Models\CameraBlacklist;

class CamerasBlacklistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $reasons = ['Пропала трансляция видео', null];

        for ($i = 0; $i < 10; $i++) {
            array_push($data, [
                'cam_numeric_id' => rand(1, 10),
                'reason' => $reasons[rand(0, 1)]
            ]);
        }

        CameraBlacklist::insert($data);
    }
}
