<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Inserts records in denormalized table `robot_fight_record`
        DB::unprepared('
            CREATE TRIGGER tr_Robots_After_Insert
            AFTER INSERT ON `robots` FOR EACH ROW
            BEGIN
                INSERT INTO `robot_fight_record` (
                    `robot_id`,
                    `name`,
                    `fights`,
                    `wins`,
                    `losses`,
                    `created_at`,
                    `updated_at`) 
                VALUES (
                    NEW.`id`,
                    NEW.`name`,
                    0,
                    0,
                    0,
                    NEW.`created_at`,
                    NEW.`updated_at`);
            END
        ');

        // Updates wins/losses in denormalized table `robot_fight_record`
        DB::unprepared('
            CREATE TRIGGER tr_Robot_Fight_Results_After_Insert
            AFTER INSERT ON `robot_fight_results` FOR EACH ROW
            BEGIN
                UPDATE `robot_fight_record`
                 SET `fights` = `fights` + 1,
                     `wins` = `wins` + 1,
                     `updated_at` = NOW()
                 WHERE `robot_id` = NEW.`winner_id`;
                     
                UPDATE `robot_fight_record`
                 SET `fights` = `fights` + 1,
                     `losses` = `losses` + 1,
                     `updated_at` = NOW()
                 WHERE `robot_id` = NEW.`loser_id`;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_Robots_After_Insert`');
        DB::unprepared('DROP TRIGGER `tr_Robot_Fight_Results_After_Insert`');
    }
}
