<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('votes', function (Blueprint $table) {
      $table->id('vote_id');
      $table->bigInteger('uik_id');
      $table->timestamp('vote_datetime')->nullable();
      $table->integer('vote_conf')->nullable();
      $table->integer('vote_orientation_name')->nullable();
      $table->bigInteger('video_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('votes');
  }
}
