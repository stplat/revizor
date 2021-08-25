<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUikTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uik_timings', function (Blueprint $table) {
            $table->id('timing_id');
            $table->bigInteger('check_id');
            $table->bigInteger('cam_numeric_id');
            $table->bigInteger('uik_id');
            $table->timestamp('date');
            $table->bigInteger('8h')->nullable();
            $table->bigInteger('10h')->nullable();
            $table->bigInteger('12h')->nullable();
            $table->bigInteger('14h')->nullable();
            $table->bigInteger('16h')->nullable();
            $table->bigInteger('18h')->nullable();
            $table->bigInteger('approved_8h')->nullable();
            $table->bigInteger('approved_10h')->nullable();
            $table->bigInteger('approved_12h')->nullable();
            $table->bigInteger('approved_14h')->nullable();
            $table->bigInteger('approved_16h')->nullable();
            $table->bigInteger('approved_18h')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uik_timings');
    }
}
