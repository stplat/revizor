<?php

use Illuminate\Database\Seeder;
use App\Models\Constant;

class ContantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Constant::insert([
            [
                'revisor_error' => 0.13,
                'boxes_width_threshold' => 0.4,
                'one_box_voters_threshold' => 1000,
                'two_boxes_voters_threshold' => 2000,
                'three_boxes_voters_threshold' => 2001,
            ],
        ]);
    }
}
