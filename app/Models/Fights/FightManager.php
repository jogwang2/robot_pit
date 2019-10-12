<?php

namespace App\Models\Fights;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Models\BaseManager;
use App\Models\Robots\Robot;
use App\Models\Fights\RobotFights;
use App\Models\Fights\RobotFightResults;
use App\Models\Fights\FightValidator;

class FightManager extends BaseManager
{
	/**
     * Fights another robot
     *
     * @param  array $input
     */
	public function fight($input)
	{
		$settings = [
            'attacker_id' => 'required',
            'defender_id' => 'required',
        ];

		// validate inputs
        $res = FightValidator::isValidInputs($input, $settings);
		if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        // check attacker robot fight count
        $attackerCount = $this->checkFightCount('attacker_id', $input['attacker_id']);
      	if($attackerCount == Config::get('constants.fights.max_attack_count')) {
            $this->setResponse(false, 'Validation Error.', 'Maximum allowable attack count reached.', 400);
            return;
      	}

      	// check defender robot fight count
        $defenderCount = $this->checkFightCount('defender_id', $input['defender_id']);
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

	/**
     * Get the latest fight results
     *
     * @param  int $count
     */
	public function getLatestRobotFights($count)
	{
		try {
			// get top $count of 
			$matches = DB::table('robot_fights')
							->join('robot_fight_results', 'robot_fights.id', '=', 'robot_fight_results.fight_id')
							->select('robot_fights.attacker_id', 'robot_fights.defender_id', 'robot_fight_results.winner_id')
							->orderBy('robot_fights.created_at', 'desc')
							->limit($count)
							->get();

			$result = $this->getRobotNamesFromMatches($matches);

			$this->setResponse(true, 'Robot Fight Results retrieved successfully.', $result);

        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when retrieving robot fight results.', $ex->getMessage(), 500);
        }
	}

	/**
     * Fights another robot
     *
     * @param  string $robotCol
     * @param  int $robotId
     * @return int count
     */
	private function checkFightCount($robotCol, $robotId)
	{
		return RobotFights::where($robotCol, '=', $robotId)
        		->whereDate('created_at', '>=', Carbon::now())->count();
	}

	/**
     * Fights another robot
     *
     * @param  int $fightId
     * @param  array $input
     */
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

	/**
     * Fights another robot
     *
     * @param  int $robotId
     */
	private function calculateRobotWinRate($robotId)
	{
		$robot = Robot::whereId($robotId)->first();
		return ($robot->speed + $robot->power) / 0.25 * $robot->weight;
	}

	/**
     * Get the robot names corresponding from robot ids in macthes
     *
     * @param  DB $matches
     * @return array $res
     */
	private function getRobotNamesFromMatches($matches)
	{
		$res = [];
		$n = 0;

		foreach ($matches as $match) {
			$res[$n]['order'] = $n+1;
			$res[$n]['info']  = $this->getRobotNamesFromMatchRows($match);
			$n++;
		}

		return $res;
	}

	/**
     * Get the robot names corresponding from robot ids in macth rows
     *
     * @param  DB $match
     * @return array $res
     */
	private function getRobotNamesFromMatchRows($match)
	{
		$res = [
			'robot_1' => $this->getRobotIdAndName($match->attacker_id),
			'robot_2' => $this->getRobotIdAndName($match->defender_id),
			'winner'  => $this->getRobotIdAndName($match->winner_id)
		];

		return $res;
	}

	/**
     * Get the robot id and name
     *
     * @param  int $robotId
     * @return array $res
     */
	private function getRobotIdAndName($robotId)
	{
		$res = [
			'id'   => $robotId,
			'name' => Robot::whereId($robotId)->value('name')
		];

		return $res;
	}
}