<?php

use Kodnificent\Covid19EstimatorApi\Http\Exception\HttpException;
use Kodnificent\Covid19EstimatorApi\RouteDispatcher;

// set appropriate api headers
header("Access-control-allow-origin: *");

try {
// dispatch routes to their handlers
$dispatcher = new RouteDispatcher();

$dispatcher->dispatch();

} catch(HttpException $e){
    http_response_code($e->getStatusCode());

    echo $e->getMessage();
} catch(\Exception $e){
    http_response_code(500);
    error_log($e->getMessage());
    echo $e->getMessage();
}