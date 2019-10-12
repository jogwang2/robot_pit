<?php

namespace App\Models;

use Validator;

abstract class BaseValidator
{
	/**
     * Validates the api inputs
     *
     * @param  array $inputs
     * @param  array $settings
     * @return array [isValid, errors]
     */
	public static function isValidInputs($input, $settings)
    {
    	$res = array(
            'isValid' => true,
            'errors' => []
        );

        $validator = Validator::make($input, $settings);

        if($validator->fails()){
            $res['isValid'] = false;
            $res['errors'] = $validator->errors();    
        }

        return $res;
    }
}