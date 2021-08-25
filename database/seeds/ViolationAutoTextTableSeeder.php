<?php

use Illuminate\Database\Seeder;
use App\Models\ViolationAutoText;

class ViolationAutoTextTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ViolationAutoText::insert([
            'check_id' => 1,
            'violation_type_id' => 1,
            'violation_text' => 'Не следует, однако, забывать о том, что постоянное информационно-техническое обеспечение нашей деятельности влечет за собой процесс внедрения и модернизации соответствующих условий активизации? Практический опыт показывает, что начало повседневной работы по формированию позиции в значительной степени обуславливает создание модели развития.',
        ]);
    }
}
