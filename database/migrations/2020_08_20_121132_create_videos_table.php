<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('videos', function (Blueprint $table) {
      $table->id('video_id');
      $table->bigInteger('check_id');
      $table->string('direct_link', 400)->nullable();
      $table->bigInteger('cam_numeric_id');
      $table->bigInteger('uik_id');
      $table->bigInteger('start_epoch')->nullable();
      $table->bigInteger('end_epoch')->nullable();
      $table->bigInteger('length')->nullable();
      $table->timestamp('start_local_datetime');
      $table->timestamp('end_local_datetime');
      $table->bigInteger('storage_server_id')->nullable();
      $table->string('processed_by_api_boxes')->nullable();
      $table->string('processed_by_api_counting')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('videos');
  }
}
