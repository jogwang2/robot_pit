<?php

namespace App\Models\Fights;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;;

use App\Models\BaseManager;
use App\Models\Robots\Robot;
use App\Models\Fights\RobotFights;
use App\Models\Fights\RobotFightResults;
use App\Models\Fights\FightValidator;

class FightManager extends BaseManager
{

	public function fight($input)
	{
		// validate inputs
		$settings = [
            'attacker_id' => 'required',
            'defender_id' => 'required',
        ];

        $res = FightValidator::isValidInputs($input, $settings);
		if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        // check attacker robot fight count
        $attackerCount = RobotFights::where('attacker_id', '=', $input['attacker_id'])
        		->whereDate('created_at', '>=', Carbon::now())->count();

      	if($attackerCount == Config::get('constants.fights.max_attack_count')) {
            $this->setResponse(false, 'Validation Error.', 'Maximum allowable attack count reached.', 400);
            return;
      	}

      	// check defender robot fight count
      	$defenderCount = RobotFights::where('defender_id', '=', $input['defender_id'])
        		->whereDate('created_at', '>=', Carbon::now())->count();

      	if($defenderCount == Config::get('constants.fights.max_defense_count')) {
            $this->setResponse(false, 'Validation Error.', 'This robot can no longer be attacked.', 400);
            return;
      	}
        
        // insert fight record in db
        try {
            $robotFight = RobotFights::create($input);
            $this->evaluateFight($robotFight->id, $input);
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when inserting robot fight.', $ex->getMessage(), 500);
        }
	}

	private function evaluateFight($fightId, $input)
	{
		// set default results
		$fightRes = [
			'fight_id'   => $fightId,
			'winner_id'  => $input['attacker_id'],
			'loser_id'   => $input['defender_id']
		];

		// get attacker/defender points
		$attackerPoints = $this->calculateRobotWinRate($input['attacker_id']);
		$defenderPoints = $this->calculateRobotWinRate($input['defender_id']);

		// evaluate points and set winner/loser accordingly
		if($defenderPoints > $attackerPoints) {
			$fightRes['winner_id'] = $input['defender_id'];
			$fightRes['loser_id'] = $input['attacker_id'];
		}

		// insert fight result to db
		try {
            $robotFightResult = RobotFightResults::create($fightRes);
            $this->setResponse(true, 'Robot Fight Results recorded successfully.', $robotFightResult->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when inserting robot fight result.', $ex->getMessage(), 500);
        }

	}

	private function calculateRobotWinRate($robotId)
	{
		$robot = Robot::whereId($robotId)->first();
		return ($robot->speed + $robot->power) / 0.25 * $robot->weight;
	}
}