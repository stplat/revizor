<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationProtocolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_protocols', function (Blueprint $table) {
            $table->id('protocol_id');
            $table->bigInteger('violation_id');
            $table->string('protocol_link', 300)->nullable();
            $table->timestamp('protocol_datetime')->nullable();
            $table->text('response_link')->nullable();
            $table->timestamp('response_datetime')->nullable();
            $table->bigInteger('applicant_id')->nullable();
            $table->string('text_type');
            $table->bigInteger('text_id');
            $table->bigInteger('decree_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_protocols');
    }
}
