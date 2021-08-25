<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id('violation_id');
            $table->bigInteger('cam_numeric_id');
            $table->bigInteger('violation_type_id')->nullable();
            $table->timestamp('creation_datetime')->nullable();
            $table->timestamp('violation_datetime_start')->nullable();
            $table->timestamp('violation_datetime_end')->nullable();
            $table->boolean('blocked')->nullable();
            $table->bigInteger('status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violations');
    }
}
