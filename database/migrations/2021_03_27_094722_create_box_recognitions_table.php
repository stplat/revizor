<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxRecognitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box_recognitions', function (Blueprint $table) {
            $table->id('recognition_id');
            $table->bigInteger('uik_id');
            $table->bigInteger('cam_numeric_id');
            $table->bigInteger('video_id');
            $table->float('cam_quality')->nullable();
            $table->timestamp('recognition_datetime')->nullable();
            $table->boolean('boxes_flag')->nullable();
            $table->integer('boxes_num')->nullable();
            $table->boolean('checking')->nullable();
            $table->boolean('checked');
            $table->bigInteger('checked_by')->nullable();
            $table->timestamp('check_datetime')->nullable();
            $table->bigInteger('recognized_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('box_recognitions');
    }
}
