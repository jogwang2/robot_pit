<?php

namespace App\Models\Fights;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
    	Log::debug('Validating fighting robots.', $input);

    	$attackerId = $input['attacker_id'];
    	$defenderId = $input['defender_id'];

        $res = array(
            'isValid' => true,
            'message' => ""
        );

    	// check attacker robot if exists and owned by user
        $attackerRobot = Robot::whereIdAndUserId($attackerId, $userId)->first();
        if (!$attackerRobot) {
            return self::returnFail('Attacker robot does not exists or is not your robot.');
        }

    	// check defender robot if exists
    	$defenderRobot = Robot::whereId($defenderId)->first();
        if (!$defenderRobot) {
            return self::returnFail('Defender robot does not exists.');
        }

    	// check defender robot if owned by user
    	$ownersDefenderRobot = Robot::whereIdAndUserId($defenderId, $userId)->first();
        if ($ownersDefenderRobot) {
            return self::returnFail('Can not attack your own robot.');
        }

        // check attacker robot fight count
        $attackerCount = self::checkFightCount('attacker_id', $attackerId);	
        if($attackerCount == Config::get('constants.fights.max_attack_count')) {
            return self::returnFail('Reached maximum allowable attacks per day for this robot.');
        }

        // check defender robot fight count
        $defenderCount = self::checkFightCount('defender_id', $defenderId);
        if($defenderCount == Config::get('constants.fights.max_defense_count')) {
            return self::returnFail('This robot can no longer be attacked.');
        }

    	Log::debug('Validating fighting robots successful.');
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

	/**
     * Check fight count
     *
     * @param  string $message
     * @return array  $res
     */
    private static function returnFail($message)
    {
    	$res = [
            'isValid' => false,
            'message' => $message
        ];
		
		Log::debug($message);
        return $res;
    }
}