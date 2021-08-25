<?php

use Illuminate\Database\Seeder;
use App\Models\VideosToFindBox;

class VideosToFindBoxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 200; $i++) {
            array_push($data, [
                'check_id' => rand(1, 4),
            ]);
        }

        VideosToFindBox::insert($data);
    }
}
