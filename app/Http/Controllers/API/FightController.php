<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Fights\FightRepository;

class FightController extends BaseController
{
	/**
     * Fights another robot
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fight(Request $request)
    {
        $input = $request->all();
        $fightRepository = new FightRepository();
        $fightRepository->fight($input);
        return $this->returnResponse($fightRepository);    
    }

	/**
     * Get latest robot fights
     *
     * @param  int  $count
     * @return \Illuminate\Http\Response
     */
    public function getLatestRobotFights(int $count)
    {
    	if(!$count) {
    		$count = Config::get('constants.latest_fight_count');
    	}
        $fightRepository = new FightRepository();
        $fightRepository->getLatestRobotFights($count);
        return $this->returnResponse($fightRepository);  
    }
}