<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRobotFightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_fights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attacker_id')->unsigned();
            $table->bigInteger('defender_id')->unsigned();
            $table->timestamps();

            $table->foreign('attacker_id')->references('id')->on('robots')->onDelete('cascade');
            $table->foreign('defender_id')->references('id')->on('robots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robot_fights');
    }
}
