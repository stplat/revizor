<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesOfficialTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('votes_official', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('uik_id');
      $table->integer('voters_turnout')->nullable();
      $table->integer('voters_voted_at_station')->nullable();
      $table->integer('voters_voted_early')->nullable();
      $table->integer('voters_voted_outside_station')->nullable();
      $table->integer('ballots_in_ballot_boxes_after_election')->nullable();
      $table->string('candidates_votes_json', 400)->nullable();
      $table->integer('ballots_valid')->nullable();
      $table->integer('ballots_invalid')->nullable();
      $table->integer('loaded_by')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('votes_official');
  }
}
