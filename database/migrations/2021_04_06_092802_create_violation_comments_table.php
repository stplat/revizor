<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violation_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->bigInteger('violation_id');
            $table->text('comment');
            $table->bigInteger('user_id');
            $table->timestamp('datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('violation_comments');
    }
}
