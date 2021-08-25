<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationImage;

use Illuminate\Support\Facades\DB;

class ViolationImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/violation_images.sql'));

        // ViolationImage::insert([
        //     [
        //         'violation_id' => 1,
        //         'image_id' => 1,
        //     ],
        //     [
        //         'violation_id' => 1,
        //         'image_id' => 2,
        //     ],
        //     [
        //         'violation_id' => 2,
        //         'image_id' => 3,
        //     ],
        //     [
        //         'violation_id' => 4,
        //         'image_id' => 4,
        //     ],
        //     [
        //         'violation_id' => 4,
        //         'image_id' => 5,
        //     ],

        // ]);
    }
}
