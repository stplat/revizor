<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosProcessingTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('videos_processing', function (Blueprint $table) {
      $table->id('processing_id');
      $table->bigInteger('check_id');
      $table->bigInteger('uik_id');
      $table->bigInteger('cam_numeric_id');
      $table->string('task_type', 30);
      $table->bigInteger('video_id');
      $table->bigInteger('server_id');
      $table->timestamp('processing_start_datetime')->nullable();
      $table->timestamp('processing_end_datetime')->nullable();
      $table->boolean('processed');
      $table->timestamp('last_processing_response')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('videos_processing');
    Schema::dropIfExists('video_processing');
  }
}
