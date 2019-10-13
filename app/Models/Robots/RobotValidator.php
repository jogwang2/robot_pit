<?php

namespace App\Models\Robots;

use Illuminate\Support\Facades\Log;

use App\Models\BaseValidator;
use App\Models\Robots\Robot;

class RobotValidator extends BaseValidator
{
    /**
     * Validates if the robot exists
     *
     * @param  int $id (robot id)
     * @return array [isExists, message, data]
     */
    public static function validateRobotExists($id, $userId)
    {
        Log::debug('Validating robots.', [ 'robot_id' => $id, 'user_id' => $userId]);

        $res = array(
            'isExists' => true,
            'message' => "",
            'data' => []
        );

        // check robot existence
        $robot = Robot::whereIdAndUserId($id, $userId)->first();
        if (!$robot) {
            $res['isExists'] = false;
            $res['message'] = 'Not Found Exception Error. The robot does not exists or is not your robot.';

            Log::debug('Validation failed. Robot not found.');
            return $res;
        }

        $res['data'] = $robot;

        Log::debug('Validation success.');
        return $res;
    }
}

