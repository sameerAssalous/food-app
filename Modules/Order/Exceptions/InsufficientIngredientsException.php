<?php

namespace Module\Order\Exceptions;

use App\Exceptions\AppException;

class InsufficientIngredientsException extends AppException
{
    public function __construct(
        $message = null,
        $errorKey = null,
        $errorName = null,
        $code = 0,
        \Exception $previous = null,
        array $extraData = []
    ) {
        parent::__construct('Insufficient Ingredients!', $errorKey, $errorName, $code, $previous, $extraData);
    }
}
