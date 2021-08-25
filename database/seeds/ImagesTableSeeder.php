<?php

use Illuminate\Database\Seeder;
use App\Models\Image;

use Illuminate\Support\Facades\DB;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/images.sql'));

        // $data = [];

        // for ($i = 0; $i < 200; $i++) {
        //     array_push($data, [
        //         'cam_numeric_id' => rand(1, 50),
        //         'raw_image' => '/public/img/1/uiks/74/123/1/raw_8uj28k9inmhbxyns.PNG',
        //         'width' => rand(200, 400),
        //         'height' => rand(150, 170),
        //         'datetime' => RandomDateHelper(),
        //     ]);
        // }

        // Image::insert($data);
        // Image::insert([
        //     [
        //         'cam_numeric_id' => 1,
        //         'raw_image' => 'public/img/1/uiks/74/123/1/raw_8uj28k9inmhbxyns.png',
        //         'width' => 640,
        //         'height' => 480,
        //         'datetime' => '2018-09-15 16:30:03.95478',
        //     ],
        //     [
        //         'cam_numeric_id' => 1,
        //         'raw_image' => 'public/img/1/uiks/74/123/1/raw_mcylpjxd.jpg',
        //         'width' => 640,
        //         'height' => 480,
        //         'datetime' => '2018-09-15 16:30:03.95478',
        //     ],
        //     [
        //         'cam_numeric_id' => 2,
        //         'raw_image' => 'public/img/1/uiks/74/123/1/raw_mcylpjxd.jpg',
        //         'width' => 640,
        //         'height' => 480,
        //         'datetime' => '2018-09-15 16:30:03.95478',
        //     ],
        //     [
        //         'cam_numeric_id' => 3,
        //         'raw_image' => 'public/img/1/uiks/74/123/1/raw_8uj28k9inmhbxyns.png',
        //         'width' => 640,
        //         'height' => 480,
        //         'datetime' => '2018-09-15 16:30:03.95478',
        //     ],
        //     [
        //         'cam_numeric_id' => 3,
        //         'raw_image' => 'public/img/1/uiks/74/123/1/raw_mcylpjxd.jpg',
        //         'width' => 640,
        //         'height' => 480,
        //         'datetime' => '2018-09-15 16:30:03.95478',
        //     ],

        // ]);
    }
}
