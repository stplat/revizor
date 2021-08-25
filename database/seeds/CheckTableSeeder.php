<?php

use Illuminate\Database\Seeder;

use App\Models\Check;

use Illuminate\Support\Facades\DB;

class CheckTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/check_table.sql'));

        // $data = [];

        // for ($i = 0; $i < 10; $i++) {
        //     array_push($data, [
        //         'check_name' => 'Проверка №' . $i,
        //         'check_type_id' => rand(1, 2),
        //         'data_type_id' => 4,
        //         'start_datetime' => RandomDateHelper(),
        //         'end_datetime' => RandomDateHelper(),
        //         'voting_date_start' => '2021-05-11 00:27:31',
        //         'voting_date_end' => '2021-05-13 00:27:31',
        //         'created_by' => 1,
        //         'active' => false,
        //     ]);
        // }

        // Check::insert($data);

        //    Check::find(1)->servers()->attach([12345, 98765, 54212]);
    }
}
