<?php

namespace Kodnificent\Covid19EstimatorApi\Tests\Unit;

use Kodnificent\Covid19EstimatorApi\Http\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{

    public function testValidateShouldFail()
    {
        $rules = [
            'prop' => function($value, $fail){
                if(is_null($value)) return $fail('prop is required');
            }
        ];
        $input = [];
        
        $this->expectException(ValidationException::class);

        validate($rules, $input);
    }

    public function testValidateShouldPass()
    {
        $rules = [
            'prop' => function($value, $fail){
                if(is_null($value)) $fail('prop is required');
            }
        ];
        $input = [
            'prop'  => 'a value'
        ];
        
        $data = validate($rules, $input);
        $this->assertEquals($input['prop'], $data['prop']);
    }
}