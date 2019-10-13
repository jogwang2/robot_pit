<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Robots\RobotManager;

class RobotController extends BaseController
{
    /**
     * Retrieves all robots.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $robotManager = new RobotManager();
        $robotManager->getRobots($user);
        return $this->returnResponse($robotManager);
    }

    /**
     * Creates a robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $input = $request->all();
        $robotManager = new RobotManager();
        $robotManager->create($user, $input);
        return $this->returnResponse($robotManager);
    }

    /**
     * Updates the robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $user = $request->user();
        $input = $request->all();
        $robotManager = new RobotManager();
        $robotManager->update($user, $id, $input);
        return $this->returnResponse($robotManager);
    }

    /**
     * Deletes the robot
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, int $id)
    {
        $user = $request->user();
        $robotManager = new RobotManager();
        $robotManager->delete($user, $id);
        return $this->returnResponse($robotManager);
    }

    /**
     * Retrieves all robots per user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {   
        $robotManager = new RobotManager();
        $robotManager->getAllRobots();
        return $this->returnResponse($robotManager);   
    }

    /**
     * Retrieves top performing robots (limited by $count).
     *
     * @param  int  $count
     * @return \Illuminate\Http\Response
     */
    public function getTopRobots(int $count)
    {
        if(!$count) {
            $count = Config::get('constants.top_robot_count');
        }
        $robotManager = new RobotManager();
        $robotManager->getTopRobots($count);
        return $this->returnResponse($robotManager);      
    }
}