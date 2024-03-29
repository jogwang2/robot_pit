<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use App\Models\BaseRepository;
use App\Models\Users\User;
use App\Models\Users\UserValidator;

class UserRepository extends BaseRepository
{
    /**
     * Registers a user
     *
     * @param array $input (user info)
     * @return null
     */
    public function register($input)
    {
        Log::info('Registering user.');

        $settings = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];

        // validate inputs
        $res = UserValidator::isValidInputs($input, $settings);
        if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        try {
            // encrypt password and save user
            $input['password'] = bcrypt($input['password']);
            $input['email_verified_at'] = Carbon::now();
            $user = User::create($input);

            // prepare output data
            $success['token'] =  $user->createToken('Personal Access Token')->accessToken;
            $success['name'] =  $user->name;

            Log::info('Registering user successful.');
            $this->setResponse(true, 'User registered successfully.', $success);
        } catch(\Exception $ex){
            Log::error('Registering user failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when registering user.', $ex->getMessage(), 500);
        }
    }


    /**
     * Login user and create token
     *
     * @param array $input (user info)
     * @return null
     */
    public function login($input)
    {
        Log::info('Logging in user.');

        $settings = [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ];

        // validate inputs
        $res = UserValidator::isValidInputs($input, $settings);
        if(!$res['isValid']){
            $this->setResponse(false, 'Validation Error.', $res['errors'], 400);
            return;
        }

        // check credentials
        $credentials = array(
            'email' => $input['email'],
            'password' => $input['password'],
        );
        if(!Auth::attempt($credentials)) {
            $this->setResponse(false, 'Unauthorized', null, 401);
            return;
        }

        // request token
        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');

        // create out data
        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer'
        ];

        Log::info('Logging in user successful.');
        $this->setResponse(true, 'Login successfully.', $data);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return null
     */
    public function logout()
    {
        Log::info('Logging out user.');
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();
        Log::info('Logging out user successful.');
        $this->setResponse(true, 'Logout successfully.', null);
    }
}