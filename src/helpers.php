<?php

use Kodnificent\Covid19EstimatorApi\Http\Exception\ValidationException;

/**
 * Run validation against a set of rules
 * 
 * @param array $rules
 * @param array $input
 * @return array $data
 */
function validate($rules, $input){
    $data = [];

    $errors = [];

    $fail = function($message){
        return $message;
    };

    foreach($rules as $attr => $pass)
    {
        $value = array_key_exists($attr, $input) ? $input[$attr] : null;
        $error_msg = $pass($value, $fail);
        if(!is_null($error_msg)){
            $errors[$attr] = $error_msg;
        }
        $data[$attr] = $value;
    }

    if(count($errors) > 0){
        $data = [
            "errors" => $errors
        ];
        throw new ValidationException(json_encode($data));
    }

    return $data;
}