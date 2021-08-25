<?php

use Illuminate\Database\Seeder;
use App\Models\BoxesInfo;

use Illuminate\Support\Facades\DB;

class BoxesInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/boxes_info.sql'));
        // $data = [];
        // $types = ['ballot_box', 'koib'];

        // // for ($i = 0; $i < 200; $i++) {
        // //     array_push($data, [
        // //         'type' => $types[rand(0, 1)],
        // //         'recognition_id' => rand(1, 50),
        // //     ]);
        // // }

        // // BoxesInfo::insert($data);
        // BoxesInfo::insert([
        //     [
        //         'recognition_id' => 1,
        //         'box_num' => 0,
        //         'box_quality' => 0.7372,
        //         'conf' => 0.92542,
        //         'type' => 'ballot_box',
        //         'type_conf' => 0.951234,
        //         'normalized_dist_k' => 0.8772612646484376,
        //         'normalized_width_k' => 0.8274720854736328,
        //         'normalized_orientation_k' => 0.7513454,
        //         'visible_rate' => 0.64531,
        //         'intersection_rate' => 0.76451,
        //         'box_bbox_coords' => '{"x1": 0.2515625, "y1": 0.470833333, "x2": 0.3953125, "y2": 0.854166667}',
        //         'centroid_k' => '{"x": 0.32343750000000004, "y": 0.6625}',
        //         'cap_type' => 'poly',
        //         'cap_ort_bbox' => '{"x1": 0.2625, "y1": 0.48125, "x2": 0.3828125, "y2": 0.647916667}',
        //         'cap_rot_bbox' => '{"top_right": {"x": 0.3828125, "y": 0.48125}, "top_left": {"x": 0.2625, "y": 0.48125}, "bottom_left": {"x": 0.2625, "y": 0.647916667}, "bottom_right": {"x": 0.3828125, "y": 0.647916667}}',
        //         'cap_angle' => 0.0,
        //         'cap_centroid_k' => '{"x": 0.32265625, "y": 0.5645833333333333}',
        //     ],
        //     [
        //         'recognition_id' => 1,
        //         'box_num' => 1,
        //         'box_quality' => 0.7868,
        //         'conf' => 0.9865,
        //         'type' => 'ballot_box',
        //         'type_conf' => 0.85124,
        //         'normalized_dist_k' => 0.7531376600477431,
        //         'normalized_width_k' => 0.8179824175994872,
        //         'normalized_orientation_k' => 0.84145561,
        //         'visible_rate' => 0.7862,
        //         'intersection_rate' => 0.94461,
        //         'box_bbox_coords' => '{"x1": 0.13125, "y1": 0.485416667, "x2": 0.271875, "y2": 0.841666667}',
        //         'centroid_k' => '{"x": 0.20156249999999998, "y": 0.6635416666666667}',
        //         'cap_type' => 'poly',
        //         'cap_ort_bbox' => '{"x1": 0.14375, "y1": 0.4875, "x2": 0.2546875, "y2": 0.647916667}',
        //         'cap_rot_bbox' => '{"top_right": {"x": 0.2546875, "y": 0.4875}, "top_left": {"x": 0.14375, "y": 0.4875}, "bottom_left": {"x": 0.14375, "y": 0.647916667}, "bottom_right": {"x": 0.2546875, "y": 0.647916667}}',
        //         'cap_angle' => 0.0,
        //         'cap_centroid_k' => '{"x": 0.19921875, "y": 0.5677083333333334}',
        //     ],
        // ]);
    }
}
