<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRobotFightResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_fight_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fight_id')->unsigned();
            $table->bigInteger('winner_id');
            $table->bigInteger('loser_id');
            $table->timestamps();

            $table->foreign('fight_id')->references('id')->on('robot_fights')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robot_fight_results');
    }
}
