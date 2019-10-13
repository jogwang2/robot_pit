<?php

namespace App\Models\Robots;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\BaseManager;
use App\Models\Robots\Robot;
use App\Models\Robots\RobotValidator;

class RobotManager extends BaseManager
{
	
	/**
     * Retrieves all robots per user.
     *
     * @param  User  $user
     */
	public function getRobots($user)
	{
        Log::info('Retrieving all user`s robots.');

		try {
            $id = $user->id;
            $robots = Robot::whereUserId($id)->get();

            Log::info('Retrieving all user`s robots successful.');
            $this->setResponse(true, 'Robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            Log::error('Retrieving all user`s robots failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when retrieving robots.', $ex->getMessage(), 500);
        }
	}

	/**
     * Inserts robot record to the database
     *
     * @param  User  $user
     * @param  array $input  (robot inputs)
     */
	public function create($user, $input)
	{
        Log::info('Creating a robot.', $input);

		$settings = [
            'user_id' => 'required',
            'name' => 'required',
            'speed' => 'required',
            'weight' => 'required',
            'power' => 'required'
        ];

        // validate inputs
        $input['user_id'] = $user->id;
		$res = RobotValidator::isValidInputs($input, $settings);
		if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        try {
        	// create a record in database
            $robot = Robot::create($input);

            Log::info('Creating a robot successful.');
            $this->setResponse(true, 'Robots created successfully.', $robot->toArray());
        } catch(\Exception $ex){
            Log::error('Creating a robot failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when creating robot.', $ex->getMessage(), 500);
        }
	}

	/**
     * Updates robot record in the database
     *
     * @param  User  $user
     * @param  int   $id  (robot id)
     * @param  array $input  (robot inputs)
     */
	public function update($user, $id, $input)
	{
        Log::info('Updating a robot.', ['robot_id' => $id, 'inputs' => $input]);

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
            $result = RobotValidator::validateRobotExists($id, $user->id);
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

            Log::info('Updating a robot successful.');
            $this->setResponse(true, 'Robot updated successfully.', $robot);
        } catch(\Exception $ex){
            Log::error('Updating a robot failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when updating robot.', $ex->getMessage(), 500);
        }
	}

	/**
     * Deletes the robot record in the database
     *
     * @param  int  $id  (robot id)
     */
	public function delete($user, $id)
	{
        Log::info('Deleting robot '. $id);

        try {
            // check if robot exists
            $result = RobotValidator::validateRobotExists($id, $user->id);
            if(!$result['isExists']) {
	            $this->setResponse(false, $result['message'], null, 404);
                return;
            }

            // delete robot if exists
            $robot = $result['data'];
            $robot->delete();

            Log::info('Deleting a robot successful.');
            $this->setResponse(true, 'Robot deleted successfully.', []);
        } catch(\Exception $ex){
            Log::error('Deleting a robot failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when deleting robot.', $ex->getMessage(), 500);
        }
	}

    /**
     * Retrieves all robots.
     *
     */
    public function getAllRobots()
    {
        Log::info('Retrieving all robots.');

        try {
            $robots = Robot::all();

            Log::info('Retrieving all robots successful.');
            $this->setResponse(true, 'Robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            Log::error('Retrieving all robots failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when retrieving robots.', $ex->getMessage(), 500);
        }
    }

    /**
     * Retrieves top performing robots limited by $count
     *
     * @param  int  $count
     */
    public function getTopRobots($count)
    {
        Log::info('Retrieving top performing robots.');

        try {
            $robots = DB::table('robot_fight_record')
                            ->select('robot_id', 'name', 'fights', 'wins', 'losses')
                            ->orderBy('wins', 'desc')
                            ->orderBy('losses', 'asc')
                            ->limit($count)
                            ->get();

            Log::info('Retrieving top performing robots successful.');
            $this->setResponse(true, 'Top robots retrieved successfully.', $robots->toArray());
        } catch(\Exception $ex){
            Log::error('Retrieving top performing robots failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when retrieving top robots.', $ex->getMessage(), 500);
        }
    }
}