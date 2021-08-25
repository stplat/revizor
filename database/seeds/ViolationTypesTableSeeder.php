<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationType;

class ViolationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ViolationType::insert([
            [
                'type_name' => 'Не хватает видео',
            ],
            [
                'type_name' => 'Сбой трансляции',
            ],
            [
                'type_name' => 'Не хватает ящиков',
            ],
            [
                'type_name' => 'Плохо видно ящики',
            ],
            [
                'type_name' => 'Не видно ящики',
            ],
            [
                'type_name' => 'Промежуточная явка не совпадает',
            ],
            [
                'type_name' => 'Явка не совпадает',
            ],
        ]);
    }
}
