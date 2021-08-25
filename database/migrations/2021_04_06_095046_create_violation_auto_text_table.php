<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationAutoTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_auto_text', function (Blueprint $table) {
            $table->id('auto_text_id');
            $table->bigInteger('check_id');
            $table->bigInteger('violation_type_id');
            $table->text('violation_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_auto_text');
    }
}
