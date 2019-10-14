<?php

namespace Tests\Unit\Helpers;

use App\Models\Users\User;
use App\Models\Users\UserRepository;

class TestHelper
{
    /**
     * Creates user
     *
     * @return void
     */
    public static function createUser($userRepository)
    {
        $input = [
            'name' => 'qwedcvgh123luv98',
            'email' => 'qwedcvgh123luv98@email.php',
            'password' => 'password',
            'confirm_password' => 'password'
        ];
        $userRepository->register($input);
    }

    /**
     * Removes user
     *
     * @return void
     */
    public static function removeUser()
    {
        $user = User::whereNameAndEmail('qwedcvgh123luv98', 'qwedcvgh123luv98@email.php')->first();
        $user->delete();
    }
}