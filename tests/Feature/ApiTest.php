<?php

namespace Kodnificent\Covid19EstimatorApi\Tests\Feature;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /**
     * Api base uri
     * 
     * @var string
     */
    protected $base_uri = 'http://localhost:7070/api/v1/on-covid-19/';

    /**
     * The http client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Input data to send with the http request
     * 
     * @var array
     */
    protected $input_data;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri'  =>  $this->base_uri,
            'http_errors' => false,
        ]);

        $this->input_data = [ 
            "region" =>
            [
                "name" => "Africa",
                "avgAge" => 19.7,
                "avgDailyIncomeInUSD" => 4,
                "avgDailyIncomePopulation" => 0.73
            ],
            "periodType" => "days",
            "timeToElapse" => 38,
            "reportedCases" => 2747,
            "population" => 92931687,
            "totalHospitalBeds" => 678874
        ];
    }

    protected function tearDown(): void
    {
        unset($this->client, $this->input_data);
    }
    
    public function testJsonEndpoint()
    {
        $res = $this->client->post('json', [
            'form_params'  =>  $this->input_data
        ]);

        // test status code
        $status = $res->getStatusCode();
        $message = $res->getBody()->read(10000);
        
        $this->assertEquals(200, $status);

        // test for the right http response header
        $content_type = $res->getHeader('Content-Type')[0];
        $this->assertEquals('application/json', $content_type);

        // test that the endpoint returns data in the specified format
        $data = json_decode($res->getBody(), true);
        
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('impact', $data);
        $this->assertArrayHasKey('severeImpact', $data);

    }

    public function testLogEndpoint()
    {}


    public function testNotFoundStatus()
    {
        $res = $this->client->get('unknown-route');

        $this->assertEquals(404, $res->getStatusCode());
    }

    public function testMethodNotAllowedStatus()
    {
        $res = $this->client->get('json');

        $this->assertEquals(405, $res->getStatusCode());
    }
}