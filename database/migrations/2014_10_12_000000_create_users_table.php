<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique();
            $table->string('user_login', 50)->unique();
            $table->string('user_pass', 255);
            $table->timestamp('creation_date')->nullable();
            $table->bigInteger('created_by_user_id')->nullable();
            $table->rememberToken()->nullable();
            $table->boolean('detections_check_access');
            $table->boolean('uiks_view_access');
            $table->boolean('events_view_access');
            $table->boolean('events_form_access');
            $table->boolean('stats_view_access');
            $table->bigInteger('role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
