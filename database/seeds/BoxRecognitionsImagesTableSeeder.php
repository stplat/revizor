<?php

use Illuminate\Database\Seeder;
use App\Models\BoxRecognitionsImage;

class BoxRecognitionsImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/box_recognitions_images.sql'));
        // $data = [];

        // for ($i = 0; $i < 100; $i++) {
        //     array_push($data, [
        //         'image_id' => rand(1, 100),
        //         'recognition_id' => rand(1, 100),
        //     ]);
        // }

        // BoxRecognitionsImage::insert($data);
        // BoxRecognitionsImage::insert([
        //     [
        //         'image_id' => 1,
        //         'recognition_id' => 1,
        //     ],
        //     [
        //         'image_id' => 2,
        //         'recognition_id' => 2,
        //     ],
        //     [
        //         'image_id' => 3,
        //         'recognition_id' => 3,
        //     ],
        //     [
        //         'image_id' => 4,
        //         'recognition_id' => 4,
        //     ],
        //     [
        //         'image_id' => 5,
        //         'recognition_id' => 5,
        //     ],
        // ]);
    }
}
