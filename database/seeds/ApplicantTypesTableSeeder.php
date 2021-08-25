<?php

use Illuminate\Database\Seeder;

use App\Models\ApplicantType;

class ApplicantTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::unprepared(file_get_contents(__DIR__ . './../dumps/violation_applicant_name.sql'));
        ApplicantType::insert([
            [
                'commission_name' => 'Участковая',
            ],
            [
                'commission_name' => 'Территориальная',
            ],
            [
                'commission_name' => 'Региональная',
            ],
            [
                'commission_name' => 'Центральная',
            ],
        ]);
    }
}
