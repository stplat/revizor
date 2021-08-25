<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntermediateVotesOfficialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intermediate_votes_official', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->bigInteger('uik_id');
            $table->integer('voters_voted_in_ballot_box');
            $table->bigInteger('loaded_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intermediate_votes_official');
    }
}
