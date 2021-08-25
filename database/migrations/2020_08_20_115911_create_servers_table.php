<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('servers', function (Blueprint $table) {
      $table->id('server_id');
      $table->bigInteger('server_type_id');
      $table->boolean('online');
      $table->string('password', 255);
      $table->string('server_ip', 15);
      $table->bigInteger('token_id')->nullable();
      $table->timestamp('last_response')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('servers');
  }
}
