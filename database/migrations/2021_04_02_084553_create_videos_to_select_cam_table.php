<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosToSelectCamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos_to_select_cam', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('video_id')->nullable();
            $table->bigInteger('cam_numeric_id')->nullable();
            $table->bigInteger('check_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos_to_select_cam');
    }
}
