<?php

namespace App\Models\Robots;

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
            return $res;
        }

        $res['data'] = $robot;
        return $res;
    }
}

