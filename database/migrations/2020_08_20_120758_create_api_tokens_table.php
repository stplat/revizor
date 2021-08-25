<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTokensTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('api_tokens', function (Blueprint $table) {
      $table->bigInteger('token_id')->primary();
      $table->bigInteger('server_id')->nullable();
      $table->string('token', 200)->nullable();
      $table->string('token_type', 50)->nullable();
      $table->string('created_by', 15)->nullable();
      $table->timestamp('creation_datetime')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('api_tokens');
  }
}
