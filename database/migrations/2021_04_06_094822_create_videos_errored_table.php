<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosErroredTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos_errored', function (Blueprint $table) {
            $table->id('error_id');
            $table->bigInteger('processing_id');
            $table->bigInteger('error_cause_id');
            $table->text('text');
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
        Schema::dropIfExists('videos_errored');
    }
}
