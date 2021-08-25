<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideosAuthTypesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::unprepared(file_get_contents(__DIR__ . './../dumps/videos_auth_types.sql'));
  }
}
