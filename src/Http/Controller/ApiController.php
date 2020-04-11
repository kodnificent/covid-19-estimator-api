<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Controller;

use Kodnificent\Covid19EstimatorApi\Exception\HttpException;
use Kodnificent\Covid19EstimatorApi\RequestLogger;
use Kodnificent\Covid19ImpactEstimator\Covid19ImpactEstimator;

class ApiController
{

    /**
     * Validation rules
     * 
     * @var array
     */
    protected $rules;

    /**
     * Request input data
     * 
     * @var array
     */
    protected $input_data;

    /**
     * Create an instance of the api controller
     * 
     * @return void
     */
    public function __construct()
    {
        $this->rules = [
            'region' => function($region, $fail){
                if(!is_array($region)) return $fail('region must be an array');
            },

            'periodType' => function($value, $fail){
                if(is_null($value)) $fail('periodType is required');
            },

            'timeToElapse' => function(){},
            'reportedCases' => function(){},
            'population' => function(){},
            'totalHospitalBeds' => function(){},
        ];

        $this->input_data = $_POST;
    }

    /**
     * Handles json post requests to the application
     * 
     * @return string
     */
    public function json()
    {
        header('Content-Type: application/json');
        
        $data = validate($this->rules, $this->input_data);

        $res = Covid19ImpactEstimator::estimate($data);

        http_response_code(200);

        $this->logAction();
        
        echo json_encode($res);
    }

    public function xml()
    {
        //
    }

    /**
     * Handles logs
     * 
     * @return string
     */
    public function logs()
    {
        header('Content-Type: text/html;charset=UTF-8');

        $logs = RequestLogger::getLogsAsString();

        http_response_code(200);

        echo $logs;
    }

    /**
     * Write events to the log file
     * 
     * @return void
     */
    protected function logAction()
    {
        return RequestLogger::log($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], http_response_code(), '48ms');
    }
}