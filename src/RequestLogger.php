<?php

namespace Kodnificent\Covid19EstimatorApi;

class RequestLogger
{

    /**
     * File storage path
     * 
     * @return string
     */
    public static $storage_path = __DIR__.'/../storage/request-logs.json';

    /**
     * Log a request to the log file
     * 
     * @param string method
     * @param string uri
     * @param int $status
     * @param string $time
     * @param int $max_log
     * @param string $storage_path
     * @return array
     */
    public static function log(string $method, string $uri, int $status, string $time, ?int $max_log = 100, ?string $storage_path = null)
    {
        $method = strtoupper($method);
        $message = compact('method', 'uri', 'status', 'time');

        $logs = static::getLogs($storage_path);
        
        array_unshift($logs, $message);
        

        if(count($logs) >= $max_log){
            // we want to save only a max of 100 logs
            $logs = array_slice($logs, 0, $max_log);
        }
        
        $new_content = compact('logs');
        $json_data = json_encode($new_content, JSON_PRETTY_PRINT);

        $file_path = $storage_path ?? static::$storage_path;
        $file = fopen($file_path, 'w');
        fwrite($file, $json_data);
        fclose($file);

        return static::getLogs($storage_path);
    }

    /**
     * Get the array of logged requests
     * 
     * @param string|null $storage_path
     * @return array
     */
    public static function getLogs(?string $storage_path = null)
    {
        $file_path = $storage_path ?? static::$storage_path;
        
        $content = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : null;
    
        // if file is empty write empty log and return empty log array
        if(is_null($content))
        {
            $logs = [];
    
            $json_data = json_encode(compact('logs'), JSON_PRETTY_PRINT);
            $file = fopen($file_path, 'w+');
            fwrite($file, $json_data);
            fclose($file);

        } else {
            $logs = $content['logs'];
        }
    
        return $logs;
    }

    /**
     * Get the request logs as string
     * 
     * @param string|null $storage_path
     * @return string
     */
    public static function getLogsAsString(?string $storage_path = null)
    {
        $logs = static::getLogs($storage_path);
    
        $string = '';
    
        foreach ($logs as $log) {
            $method = $log['method'];
            $uri = $log['uri'];
            $status = $log['status'];
            $time = $log['time'];
            $string .= "$method&emsp;&emsp;$uri&emsp;&emsp;$status&emsp;&emsp;$time\r\n";
        }
    
        return nl2br($string);
    }

    /**
     * Clear all request logs
     * 
     * @param string|null $storage_path
     * @return void
     */
    public static function clearLogs(?string $storage_path = null)
    {
        $logs = [];
        $new_content = compact('logs');
        $json_data = json_encode($new_content, JSON_PRETTY_PRINT);

        $file_path = $storage_path ?? static::$storage_path;
        $file = fopen($file_path, 'w');
        fwrite($file, $json_data);
        fclose($file);
    }
}