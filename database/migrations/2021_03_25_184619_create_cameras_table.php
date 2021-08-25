<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->bigIncrements('cam_numeric_id');
            $table->bigInteger('check_id');
            $table->bigInteger('uik_id');
            $table->string('cam_id', 100);
            $table->integer('cam_num');
            $table->boolean('main')->nullable();
            $table->boolean('skip_votes_summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cameras');
    }
}
