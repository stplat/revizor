<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VotesDeviation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes_deviation', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('uik_id');
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->float('deviation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes_deviation');
    }
}
