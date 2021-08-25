<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataTypesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::unprepared(file_get_contents(__DIR__ . './../dumps/data_types.sql'));
  }
}
