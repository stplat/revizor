<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationStatus;

class ViolationStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ViolationStatus::insert([
            [
                'status_name' => 'Новое событие',
            ],
            [
                'status_name' => 'Не нарушение',
            ],
            [
                'status_name' => 'Сформирована жалоба',
            ],
            [
                'status_name' => 'Жалоба отправлена',
            ],
            [
                'status_name' => 'Ответ получен',
            ],
            [
                'status_name' => 'Проблема решена',
            ],
            [
                'status_name' => 'Проблема не решена',
            ],
        ]);
    }
}
