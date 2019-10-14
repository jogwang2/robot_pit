<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Unit\Helpers\TestHelper;
use App\Models\Users\UserManager;

class UserManagerTest extends TestCase
{
    /**
     * Test register user return success
     *
     * @return void
     */
    public function testRegisterReturnSuccess()
    {
        $userManager = new UserManager();
    	TestHelper::createUser($userManager);

        $this->assertTrue($userManager->success);

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
    	$userManager = new UserManager();
    	$userManager->register($input);

        $this->assertFalse($userManager->success);
        $this->assertTrue($userManager->message === 'Validation Error.');
    }

    /**
     * Test register user return exception fail
     *
     * @return void
     */
    public function testRegisterReturnExceptionFail()
    {
        $userManager = new UserManager();
        TestHelper::createUser($userManager);
    	
    	// register the same user again to catch unique users error
        TestHelper::createUser($userManager);

        $this->assertFalse($userManager->success);
        $this->assertTrue($userManager->message === 'Error encountered when registering user.');

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
        $userManager = new UserManager();
        TestHelper::createUser($userManager);

    	// login user
    	$input = [
            'email' => 'qwedcvgh123luv98@email.php',
            'password' => 'password'
    	];
    	$userManager->login($input);

        $this->assertTrue($userManager->success);

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
    	$userManager = new UserManager();
    	$userManager->login($input);

        $this->assertFalse($userManager->success);
        $this->assertTrue($userManager->message === 'Validation Error.');
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
    	$userManager = new UserManager();
    	$userManager->login($input);

        $this->assertFalse($userManager->success);
        $this->assertTrue($userManager->message === 'Unauthorized');
    }
}
