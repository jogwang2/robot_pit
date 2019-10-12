<?php

namespace App\Models\Robots;

use Illuminate\Support\Facades\DB;

use App\Models\BaseManager;
use App\Models\Robots\Robot;
use App\Models\Robots\RobotValidator;

class RobotManager extends BaseManager
{
	/**
     * Retrieves all robots.
     *
     */
	public function getAllRobots()
	{
		try {
            $robots = Robot::all();
            $this->setResponse(true, 'Robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when retrieving robots.', $ex->getMessage(), 500);
        }
	}
	
	/**
     * Retrieves all robots.
     *
     * @param  int  $id  (user id)
     */
	public function getRobots($id)
	{
		try {
            $robots = Robot::whereUserId($id)->get();
            $this->setResponse(true, 'Robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when retrieving robots.', $ex->getMessage(), 500);
        }
	}

    public function getTopRobots($count)
    {
        try {
            $robots = DB::table('robot_fight_record')
                            ->select('robot_id', 'name', 'fights', 'wins', 'losses')
                            ->orderBy('wins', 'desc')
                            ->orderBy('losses', 'asc')
                            ->limit($count)
                            ->get();
            $this->setResponse(true, 'Top robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when retrieving top robots.', $ex->getMessage(), 500);
        }
    }

	/**
     * Inserts robot record to the database
     *
     * @param  array  $input  (robot inputs)
     */
	public function create($input)
	{
		$settings = [
            'user_id' => 'required',
            'name' => 'required',
            'speed' => 'required',
            'weight' => 'required',
            'power' => 'required'
        ];

        // validate inputs
		$res = RobotValidator::isValidInputs($input, $settings);
		if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        try {
        	// create a record in database
            $robot = Robot::create($input);
            $this->setResponse(true, 'Robots created successfully.', $robot->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when creating robot.', $ex->getMessage(), 500);
        }
	}

	/**
     * Updates robot record in the database
     *
     * @param  int  $id  (robot id)
     * @param  array  $input  (robot inputs)
     */
	public function update($id, $input)
	{
		$settings = [
            'name' => 'required',
            'speed' => 'required',
            'weight' => 'required',
            'power' => 'required'
        ];

        // validate inputs
		$res = RobotValidator::isValidInputs($input, $settings);
		if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        try {
        	// check if robot exists
            $result = RobotValidator::validateRobotExists($id);
            if(!$result['isExists']) {
	            $this->setResponse(false, $result['message'], null, 404);
                return;
            }
            // update robot if exists
            $robot = $result['data'];
            $robot->name = $input['name'];
            $robot->speed = $input['speed'];
            $robot->weight = $input['weight'];
            $robot->power = $input['power'];
            $robot->save();

            $this->setResponse(true, 'Robot updated successfully.', $robot->toArray());
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when updating robot.', $ex->getMessage(), 500);
        }
	}

	/**
     * Deletes the robot record in the database
     *
     * @param  int  $id  (robot id)
     */
	public function delete($id)
	{

        try {
            // check if robot exists
            $result = RobotValidator::validateRobotExists($id);
            if(!$result['isExists']) {
	            $this->setResponse(false, $result['message'], null, 404);
                return;
            }

            // delete robot if exists
            $robot = $result['data'];
            $robot->delete();

            $this->setResponse(true, 'Robot deleted successfully.', []);
        } catch(\Exception $ex){
            $this->setResponse(false, 'Error encountered when deleting robot.', $ex->getMessage(), 500);
        }
	}
}