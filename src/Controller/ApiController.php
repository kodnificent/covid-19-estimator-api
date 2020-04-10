<?php

namespace Kodnificent\Covid19EstimatorApi\Controller;

class ApiController
{

    public function __construct()
    {  
        // set appropriate api headers
        header("Access-control-allow-origin: *");
    }

    public function json()
    {
        
    }

    public function xml()
    {
        //
    }
}