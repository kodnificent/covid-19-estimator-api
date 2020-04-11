<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Exception;

use Throwable;

class NotFoundException extends HttpException
{

    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct(404, $message, $previous);
    }
}