<?php

$route->post('/api/v1/on-covid-19[/json]', 'ApiController@json');

$route->post('/api/v1/on-covid-19/xml', 'ApiController@xml');

$route->get('/api/v1/on-covid-19/logs', 'ApiController@logs');