<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes_info', function (Blueprint $table) {
            $table->id('box_id');
            $table->bigInteger('recognition_id')->nullable();
            $table->integer('box_num')->nullable();
            $table->float('box_quality')->nullable();
            $table->float('conf')->nullable();
            $table->string('type', 50);
            $table->float('type_conf')->nullable();
            $table->float('normalized_dist_k')->nullable();
            $table->float('normalized_width_k')->nullable();
            $table->float('normalized_orientation_k')->nullable();
            $table->float('visible_rate')->nullable();
            $table->float('intersection_rate')->nullable();
            $table->string('box_bbox_coords', 400)->nullable();
            $table->string('centroid_k', 100)->nullable();
            $table->string('cap_type')->nullable();
            $table->string('cap_ort_bbox', 400)->nullable();
            $table->string('cap_rot_bbox', 400)->nullable();
            $table->string('cap_angle')->nullable();
            $table->string('cap_centroid_k', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes_info');
    }
}
