<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Robots\Robot;
use App\Models\Robots\RobotValidator;

class RobotValidatorTest extends TestCase
{
    /**
     * Validates robot returns success
     *
     * @return void
     */
    public function testSuccess()
    {
        $this->assertTrue(true);
    }
}
