<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_status_changes', function (Blueprint $table) {
            $table->id('change_id');
            $table->bigInteger('violation_id')->nullable();
            $table->bigInteger('changed_by');
            $table->bigInteger('status_id')->nullable();
            $table->timestamp('status_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_status_changes');
    }
}
