<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationApplicantNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_applicant_name', function (Blueprint $table) {
            $table->id('applicant_id');
            $table->integer('region_number');
            $table->string('name', 70);
            $table->boolean('is_organisation');
            $table->bigInteger('applicant_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_applicant_name');
    }
}
