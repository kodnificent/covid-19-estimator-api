<?php

namespace Kodnificent\Covid19EstimatorApi\Tests\Unit;

use Kodnificent\Covid19EstimatorApi\RequestLogger;
use PHPUnit\Framework\TestCase;

class RequestLoggerTest extends TestCase
{

    private $storage_path =  __DIR__.'/../../storage/test-request-logs.json';

    public function testClearRequestLogs()
    {
        RequestLogger::log('DELETE', '/you-should-clear', 404, '690ms', 100, $this->storage_path);
        
        RequestLogger::clearLogs($this->storage_path);
        
        $logs = RequestLogger::getLogs($this->storage_path);

        $this->assertEquals(0, count($logs));
    }

    public function testGetLogs()
    {
        $logs = RequestLogger::getLogs($this->storage_path);

        $this->assertIsArray($logs);
    }

    public function testGetRequestLogsAsString()
    {
        RequestLogger::log('GET', '/test-request-as-string', 404, '690ms', 100, $this->storage_path);

        $string = RequestLogger::getLogsAsString($this->storage_path);

        $this->assertMatchesRegularExpression("/GET&emsp;&emsp;\/test-request-as-string&emsp;&emsp;404&emsp;&emsp;690ms<br \/>\r\n/", $string);
    }

    public function testLogRequest()
    {
        RequestLogger::log('GET', '/hello-world', 404, '690ms', 100, $this->storage_path);
        RequestLogger::log('POST', '/second-request', 404, '690ms', 100, $this->storage_path);
        RequestLogger::log('GET', '/third-request', 404, '690ms', 100, $this->storage_path);

        $logs = RequestLogger::getLogs($this->storage_path);
        
        $this->assertFileExists($this->storage_path);

        // third log (latest)
        $this->assertFileExists($this->storage_path);
        $this->assertEquals('GET', $logs[0]['method']);
        $this->assertEquals('/third-request', $logs[0]['uri']);
        $this->assertEquals(404, $logs[0]['status']);
        $this->assertEquals('690ms', $logs[0]['time']);

        // second log
        $this->assertFileExists($this->storage_path);
        $this->assertEquals('POST', $logs[1]['method']);
        $this->assertEquals('/second-request', $logs[1]['uri']);
        $this->assertEquals(404, $logs[1]['status']);
        $this->assertEquals('690ms', $logs[1]['time']);

        // first log
        $this->assertEquals('GET', $logs[2]['method']);
        $this->assertEquals('/hello-world', $logs[2]['uri']);
        $this->assertEquals(404, $logs[2]['status']);
        $this->assertEquals('690ms', $logs[2]['time']);
    }
}