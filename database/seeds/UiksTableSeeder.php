<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UiksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/uiks.sql'));
        // $data = [];
        // $strings = ['MdTZdzcG8Q9hZ35lIKoVOXLdU5vBOJ4BRFxr7soEOTJESfjnM8', ''];

        // for ($i = 0; $i < 50; $i++) {
        //     $region = rand(1, 10);

        //     array_push($data, [
        //         'check_id' => rand(1, 2),
        //         'region' => 'Название региона №' . $region,
        //         'region_number' => $region,
        //         'uik_number' => $i + 1,
        //         'address' => 'Адресс УИКа №' . $strings[rand(0, 1)] . ($i + 1),
        //         'timezone_offset' => 1,
        //         'voters_registered' => rand(100, 700),
        //         'latitude' => DatabaseSeeder::randomFloat(55.1, 55.3),
        //         'longitude' => DatabaseSeeder::randomFloat(61.15, 61.6),
        //         'tik' => null,
        //     ]);
        // }

        // DB::table('uiks')->insert($data);
    }
}
