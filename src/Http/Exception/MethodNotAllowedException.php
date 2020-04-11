<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Exception;

use Throwable;

class MethodNotAllowedException extends HttpException
{

    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct(405, $message, $previous);
    }
}