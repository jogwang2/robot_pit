<?php

namespace App\Models\Fights;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

use App\Models\BaseValidator;
use App\Models\Robots\Robot;

class FightValidator extends BaseValidator
{
	/**
     * Validates the fighting robots
     *
     * @param  array $inputs
     * @param  array $settings
     * @return array [isValid, errors]
     */
	public static function validateFightingRobots($userId, $input)
    {
    	$attackerId = $input['attacker_id'];
    	$defenderId = $input['defender_id'];

        $res = array(
            'isValid' => true,
            'message' => ""
        );

    	// check attacker robot if exists and owned by user
        $attackerRobot = Robot::whereIdAndUserId($attackerId, $userId)->first();
        if (!$attackerRobot) {
            $res['isValid'] = false;
            $res['message'] = 'Attacker robot does not exists or is not your robot.';
            return $res;
        }

    	// check defender robot if exists
    	$defenderRobot = Robot::whereId($defenderId)->first();
        if (!$defenderRobot) {
            $res['isValid'] = false;
            $res['message'] = 'Defender robot does not exists.';
            return $res;
        }

    	// check defender robot if owned by user
    	$ownersDefenderRobot = Robot::whereIdAndUserId($defenderId, $userId)->first();
        if ($ownersDefenderRobot) {
            $res['isValid'] = false;
            $res['message'] = 'Can not attack your own robot.';
            return $res;
        }

        // check attacker robot fight count
        $attackerCount = self::checkFightCount('attacker_id', $attackerId);	
        if($attackerCount == Config::get('constants.fights.max_attack_count')) {
            $res['isValid'] = false;
            $res['message'] = 'Reached maximum allowable attacks per day for this robot.';
            return $res;
        }

        // check defender robot fight count
        $defenderCount = self::checkFightCount('defender_id', $defenderId);
        if($defenderCount == Config::get('constants.fights.max_defense_count')) {
            $res['isValid'] = false;
            $res['message'] = 'This robot can no longer be attacked.';
            return $res;
        }

        return $res;
    }

    /**
     * Check fight count
     *
     * @param  string $robotCol
     * @param  int $robotId
     * @return int count
     */
    public static function checkFightCount($robotCol, $robotId)
    {
        return RobotFights::where($robotCol, '=', $robotId)
                ->whereDate('created_at', '>=', Carbon::now())->count();
    }
}