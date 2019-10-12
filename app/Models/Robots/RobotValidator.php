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
    public static function validateRobotExists($id)
    {
        $res = array(
            'isExists' => true,
            'message' => "",
            'data' => []
        );

        $robot = Robot::find($id);
        if (!$robot) {
            $res['isExists'] = false;
            $res['message'] = sprintf('Not Found Exception Error. Robot [%d] does not exists.', $id);
        }

        $res['data'] = $robot;
        return $res;
    }
}

