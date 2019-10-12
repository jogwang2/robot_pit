<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRobotFightRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_fight_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('robot_id')->unsigned();
            $table->string('name', 100);
            $table->integer('fights');
            $table->integer('wins');
            $table->integer('losses');
            $table->timestamps();

            $table->foreign('robot_id')->references('id')->on('robots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robot_fight_record');
    }
}
