<?php

namespace Kodnificent\Covid19EstimatorApi\Http\Exception;

use RuntimeException;
use Throwable;

class HttpException extends RuntimeException
{
    private $status_code;

    public function __construct(int $status_code = 500, string $message = '', $code, ?Throwable $previous = null)
    {
        $this->status_code = $status_code;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }
}