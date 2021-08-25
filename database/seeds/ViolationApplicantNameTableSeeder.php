<?php

use Illuminate\Database\Seeder;

use App\Models\Applicant;

class ViolationApplicantNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::unprepared(file_get_contents(__DIR__ . './../dumps/violation_applicant_name.sql'));
        Applicant::insert([
            [
                'region_number' => 7,
                'name' => 'Иванов Иван Иванович',
                'is_organisation' => false,
                'applicant_type_id' => 3
            ],
            [
                'region_number' => 15,
                'name' => 'Семёнов Семён Семёнович',
                'is_organisation' => false,
                'applicant_type_id' => 3
            ]
        ]);
    }
}
