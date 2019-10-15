<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Robots\RobotRepository;
use App\Models\Robots\Robot;

class RobotController extends BaseController
{
    /**
     * Retrieves all robots.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRobots()
    {
        $robotRepository = new RobotRepository();
        $robotRepository->getRobots();
        return $this->returnResponse($robotRepository);
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
        $robotRepository = new RobotRepository();
        $robotRepository->create($input);
        return $this->returnResponse($robotRepository);
    }

    /**
     * Updates the robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // TODO: implement route model binding that can handle 404 case
        $input = $request->all();
        $robotRepository = new RobotRepository();
        $robotRepository->update($id, $input);
        return $this->returnResponse($robotRepository);
    }

    /**
     * Deletes the robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, int $id)
    {
        // TODO: implement route model binding that can handle 404 case
        $robotRepository = new RobotRepository();
        $robotRepository->delete($id);
        return $this->returnResponse($robotRepository);
    }

    /**
     * Retrieves all robots per user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {   
        $robotRepository = new RobotRepository();
        $robotRepository->getAllRobots();
        return $this->returnResponse($robotRepository);   
    }

    /**
     * Retrieves top performing robots (limited by $count).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTopRobots(Request $request)
    {
        $count = $request->count;
        if(!$count) {
            $count = Config::get('constants.top_robot_count');
        }

        $robotRepository = new RobotRepository();
        $robotRepository->getTopRobots($count);
        return $this->returnResponse($robotRepository);      
    }
}