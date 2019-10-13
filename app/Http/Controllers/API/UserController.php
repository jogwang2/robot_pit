<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Users\UserManager;

class UserController extends BaseController
{
    /**
     * Registers a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $input = $request->all();

        $userManager = new UserManager();
        $userManager->register($input);
        return $this->returnResponse($userManager);
    }

    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $userManager = new UserManager();
        $userManager->login($input);
        return $this->returnResponse($userManager);
    }

    /**
     * Logout user and revoke token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $userManager = new UserManager();
        $userManager->logout($user);
        return $this->returnResponse($userManager);
    }
}