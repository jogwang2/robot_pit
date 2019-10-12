<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Fights\FightManager;

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
        $fightManager = new FightManager();
        $fightManager->fight($input);
        return $this->returnResponse($fightManager);    
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
        $fightManager = new FightManager();
        $fightManager->getLatestRobotFights($count);
        return $this->returnResponse($fightManager);  
    }
}