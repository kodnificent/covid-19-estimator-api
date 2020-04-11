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

    $fail = function($message){
        throw new ValidationException($message);
    };

    foreach($rules as $attr => $pass)
    {
        $value = array_key_exists($attr, $input) ? $input[$attr] : null;
        $pass($value, $fail);
        $data[$attr] = $value;
    }

    return $data;
}