<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Robots\RobotManager;
use App\Imports\RobotsImport;
use Maatwebsite\Excel\Facades\Excel;

class RobotController extends BaseController
{
    /**
     * Retrieves all robots.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $robotManager = new RobotManager();
        $robotManager->getAllRobots();
        return $this->returnResponse($robotManager);      
    }

    /**
     * Retrieves all robots.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function getAll(int $user_id)
    {
        $robotManager = new RobotManager();
        $robotManager->getRobots($user_id);
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

    /**
     * Creates a robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $robotManager = new RobotManager();
        $robotManager->create($input);
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
        $input = $request->all();
        $robotManager = new RobotManager();
        $robotManager->update($id, $input);
        return $this->returnResponse($robotManager);
    }

    /**
     * Deletes the robot
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        $robotManager = new RobotManager();
        $robotManager->delete($id);
        return $this->returnResponse($robotManager);
    }

    /**
     * Imports robot via CSV file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $input = $request->file('file');
        // Excel::import(new RobotsImport, $input);
        
        // return back();
        // $robotManager = new RobotManager();
        // $robotManager->import($input);
        // return $this->returnResponse($robotManager);
    }
}