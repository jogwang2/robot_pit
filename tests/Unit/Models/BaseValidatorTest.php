<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\BaseValidator;

class BaseValidatorTest extends TestCase
{
    /**
     * Test for success scenario
     *
     * @return void
     */
    public function testSuccess()
    {

    	$input = [
    		'id' => 1,
    		'name' => 'myName',
    		'email' => 'unit@test.php',
    		'password' => 'password',
    		'confirm_password' => 'password'
    	];

    	$settings = [
    		'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];

        $result = BaseValidator::isValidInputs($input, $settings);

        $this->assertTrue($result['isValid']);
        $this->assertEmpty($result['errors']);
    }


    /**
     * Test for fail scenario
     *
     * @return void
     */
    public function testFail()
    {

    	$input = [
    		'id' => 1,
    		'name' => 'myName',
    		'email' => 'unit@test.php',
    		'password' => 'password',
    	];

    	$settings = [
    		'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ];

        $result = BaseValidator::isValidInputs($input, $settings);

        $this->assertFalse($result['isValid']);
    }
}
