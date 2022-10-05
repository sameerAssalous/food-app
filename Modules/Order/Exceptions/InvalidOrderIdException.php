<?php

namespace Module\Order\Exceptions;

use App\Exceptions\AppException;

class InvalidOrderIdException extends AppException
{
    public function __construct(
        $message = null,
        $errorKey = null,
        $errorName = null,
        $code = 0,
        \Exception $previous = null,
        array $extraData = []
    ) {
        parent::__construct('Invalid product Id', $errorKey, $errorName, $code, $previous, $extraData);
    }
}
