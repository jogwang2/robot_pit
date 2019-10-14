<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Users\UserRepository;

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

        $userRepository = new UserRepository();
        $userRepository->register($input);
        return $this->returnResponse($userRepository);
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

        $userRepository = new UserRepository();
        $userRepository->login($input);
        return $this->returnResponse($userRepository);
    }

    /**
     * Logout user and revoke token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $userRepository = new UserRepository();
        $userRepository->logout();
        return $this->returnResponse($userRepository);
    }
}