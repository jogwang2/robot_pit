<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Unit\Helpers\TestHelper;
use App\Models\Users\UserRepository;

class UserRepositoryTest extends TestCase
{
    /**
     * Test register user return success
     *
     * @return void
     */
    public function testRegisterReturnSuccess()
    {
        $userRepository = new UserRepository();
    	TestHelper::createUser($userRepository);

        $this->assertTrue($userRepository->success);

        // remove registered user in DB after test
        TestHelper::removeUser();
    }

    /**
     * Test register user return validation fail
     *
     * @return void
     */
    public function testRegisterReturnValidationFail()
    {
    	$input = [
    		'name' => 'qwedcvgh123luv98',
            'email' => 'qwedcvgh123luv98@email.php',
            'password' => 'password',
    	];
    	$userRepository = new UserRepository();
    	$userRepository->register($input);

        $this->assertFalse($userRepository->success);
        $this->assertTrue($userRepository->message === 'Validation Error.');
    }

    /**
     * Test register user return exception fail
     *
     * @return void
     */
    public function testRegisterReturnExceptionFail()
    {
        $userRepository = new UserRepository();
        TestHelper::createUser($userRepository);
    	
    	// register the same user again to catch unique users error
        TestHelper::createUser($userRepository);

        $this->assertFalse($userRepository->success);
        $this->assertTrue($userRepository->message === 'Error encountered when registering user.');

        // remove registered user in DB after test
        TestHelper::removeUser();
    }

    /**
     * Test login user return success
     *
     * @return void
     */
    public function testLoginReturnSuccess()
    {
		// register user
        $userRepository = new UserRepository();
        TestHelper::createUser($userRepository);

    	// login user
    	$input = [
            'email' => 'qwedcvgh123luv98@email.php',
            'password' => 'password'
    	];
    	$userRepository->login($input);

        $this->assertTrue($userRepository->success);

        // remove registered user in DB after test
        TestHelper::removeUser();
    }

    /**
     * Test login user return validation fail
     *
     * @return void
     */
    public function testLoginReturnValidationFail()
    {
    	// login user
    	$input = [
            'email' => 'email',
            'password' => 'password'
    	];
    	$userRepository = new UserRepository();
    	$userRepository->login($input);

        $this->assertFalse($userRepository->success);
        $this->assertTrue($userRepository->message === 'Validation Error.');
    }

    /**
     * Test login user return unauthorized fail
     *
     * @return void
     */
    public function testLoginReturnUnauthorizedFail()
    {
    	// login user
    	$input = [
            'email' => 'test@email.php',
            'password' => 'password'
    	];
    	$userRepository = new UserRepository();
    	$userRepository->login($input);

        $this->assertFalse($userRepository->success);
        $this->assertTrue($userRepository->message === 'Unauthorized');
    }
}
