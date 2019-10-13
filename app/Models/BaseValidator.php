<?php

namespace App\Models;

use Validator;
use Illuminate\Support\Facades\Log;

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
        Log::debug('Validating inputs against settings.', [ 'input' => $input, 'settings' => $settings]);

    	$res = array(
            'isValid' => true,
            'errors' => []
        );

        $validator = Validator::make($input, $settings);

        if($validator->fails()){
            Log::debug('Validation failed.', ['error' => $validator->errors()]);
            $res['isValid'] = false;
            $res['errors'] = $validator->errors();    
        }

        Log::debug('Validation success.');
        return $res;
    }
}