<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Exception;

use Throwable;

class ValidationException extends HttpException
{

    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct(422, $message, $previous);
    }
}