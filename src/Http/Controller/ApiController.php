<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Controller;

use Kodnificent\Covid19EstimatorApi\RequestLogger;
use Kodnificent\Covid19ImpactEstimator\Covid19ImpactEstimator;
use Spatie\ArrayToXml\ArrayToXml;

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
                //if(!is_array($region)) return $fail('region must be an array');
            },

            'periodType' => function($value, $fail){
                //if(is_null($value)) $fail('periodType is required');
            },

            'timeToElapse' => function(){},
            'reportedCases' => function(){},
            'population' => function(){},
            'totalHospitalBeds' => function(){},
        ];

        $this->input_data = $_POST;
    }

    /**
     * Handles json output
     * 
     * @return string
     */
    public function json()
    {
        header('Content-Type: application/json');
        
        $data = validate($this->rules, $this->input_data);

        $estimate = Covid19ImpactEstimator::estimate($data);

        $res = json_encode($estimate);

        $this->logAction();

        http_response_code(200);
        
        echo $res;
    }

    /**
     * Handles xml output
     * 
     * @return string
     */
    public function xml()
    {
        header('Content-Type: application/xml');

        $data = validate($this->rules, $this->input_data);

        $estimate = Covid19ImpactEstimator::estimate($data);

        $res = ArrayToXml::convert($estimate);

        $this->logAction();

        http_response_code(200);

        echo $res;
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

        $this->logAction();

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
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $status = http_response_code();
        $req_duration = ceil((microtime(true) - REQUEST_START) * 1000) . 'ms';

        return RequestLogger::log($method, $uri, $status, $req_duration);
    }
}