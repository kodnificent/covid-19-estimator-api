<?php

use Kodnificent\Covid19EstimatorApi\RouteDispatcher;

// dispatch routes to their handlers
$dispatcher = new RouteDispatcher();

$dispatcher->dispatch();