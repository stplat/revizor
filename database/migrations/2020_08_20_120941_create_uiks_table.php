<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uiks', function (Blueprint $table) {
            $table->id('uik_id');
            $table->bigInteger('check_id');
            $table->string('region', 100);
            $table->integer('region_number');
            $table->integer('uik_number');
            $table->string('address', 500);
            $table->integer('timezone_offset');
            $table->integer('voters_registered')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->text('tik')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uiks');
    }
}
