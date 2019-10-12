<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

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
}