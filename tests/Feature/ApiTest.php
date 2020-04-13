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
        ]);

        $this->input_data = json_decode(file_get_contents(__DIR__.'/../test-data.json'), true);
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

    public function testXmlEndpoint()
    {
        $res = $this->client->post('xml', [
            'form_params'  =>  $this->input_data
        ]);

        // test status code
        $status = $res->getStatusCode();
        $message = $res->getBody()->read(10000);
        
        $this->assertEquals(200, $status);

        // test for the right http response header
        $content_type = $res->getHeader('Content-Type')[0];
        $this->assertEquals('application/xml', $content_type);

        // test that the endpoint returns data in the specified format
        $data = json_decode(json_encode(simplexml_load_string($res->getBody())), true);
        
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('impact', $data);
        $this->assertArrayHasKey('severeImpact', $data);
    }

    public function testLogEndpoint()
    {
        $res = $this->client->get('logs');

        $this->assertEquals(200, $res->getStatusCode());
        
        $content_type = $res->getHeader('Content-Type')[0];
        $this->assertMatchesRegularExpression("/text\/plain/", $content_type);
    }

    public function testNotFoundStatus()
    {
        $res = $this->client->get('unknown-route', [
            'http_errors' => false,
        ]);

        $this->assertEquals(404, $res->getStatusCode());
    }

    public function testMethodNotAllowedStatus()
    {
        $res = $this->client->get('json', [
            'http_errors' => false,
        ]);

        $this->assertEquals(405, $res->getStatusCode());
    }
}