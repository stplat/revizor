<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_servers', function (Blueprint $table) {
            $table->id('storage_server_id');
            $table->bigInteger('check_id');
            $table->boolean('auth_needed');
            $table->bigInteger('auth_type_id')->nullable();
            $table->text('storage_link');
            $table->string('auth_login')->nullable();
            $table->string('auth_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_servers');
    }
}
