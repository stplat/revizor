<?php

use Illuminate\Database\Seeder;
use App\Models\CheckedVideo;

class CheckedVideosTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
//    DB::unprepared(file_get_contents(__DIR__ . './../dumps/votes.sql'));
      $data = [];

      for ($i = 0; $i < 200; $i++) {
          array_push($data, [
              'check_id' => rand(1, 4),
          ]);
      }

      CheckedVideo::insert($data);
  }
}
