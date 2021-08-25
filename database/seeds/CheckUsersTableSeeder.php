<?php

use Illuminate\Database\Seeder;
use App\Models\CheckUser;

class CheckUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 5; $i++) {
            array_push($data, [
                'check_id' => rand(1, 4),
                'user_id' => rand(1, 7),
            ]);
        }

        CheckUser::insert($data);
    }
}
