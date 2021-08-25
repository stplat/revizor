<?php

use Illuminate\Database\Seeder;
use App\Models\UikLabel;

class UikLabelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 50; $i++) {
            $region = rand(1, 10);

            array_push($data, [
                'uik_id' => rand(1, 50),
                'label_id' => rand(5, 7)
            ]);
        }

        UikLabel::insert($data);
    }
}
