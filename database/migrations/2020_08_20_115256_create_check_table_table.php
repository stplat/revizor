<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckTableTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('check_table', function (Blueprint $table) {
      $table->id('check_id');
      $table->string('check_name', 50);
      $table->bigInteger('check_type_id');
      $table->bigInteger('data_type_id');
      $table->timestamp('start_datetime')->nullable();
      $table->timestamp('end_datetime')->nullable();
      $table->timestamp('voting_date_start');
      $table->timestamp('voting_date_end');
      $table->bigInteger('created_by');
      $table->boolean('active');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('check_table');
  }
}
