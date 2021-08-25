<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationComment;

class ViolationCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ViolationComment::insert([
            [
                'violation_id' => 1,
                'comment' => 'Какой-то комментарий',
                'user_id' => 1,
                'datetime' => RandomDateHelper()
            ],
            [
                'violation_id' => 1,
                'comment' => 'Какой-то комментарий номер 2',
                'user_id' => 1,
                'datetime' => RandomDateHelper()
            ]
        ]);
    }
}
