<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckingVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checking_videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('video_id')->nullable();
            $table->bigInteger('cam_numeric_id')->nullable();
            $table->bigInteger('check_id');
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checking_videos');
    }
}
