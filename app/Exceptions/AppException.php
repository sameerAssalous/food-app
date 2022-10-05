<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class AppException extends \Exception
{
    private $errorKey ;
    private $errorName;
    private $httpStatus = Response::HTTP_BAD_REQUEST;
    private $extraData;

    public function __construct(
        $message = null,
        $errorKey = null,
        $errorName = null,
        $code = 0,
        \Exception $previous = null,
        array $extraData = []
    ) {
        $this->errorKey = $errorKey;
        $this->errorName = $errorName;
        $this->extraData = $extraData;

        parent::__construct($message, $code, $previous);
    }

    public function getErrorKey()
    {
        return $this->errorKey;
    }

    public function getErrorName()
    {
        return $this->errorName;
    }

    public function getStatusCode()
    {
        return $this->httpStatus;
    }

    protected function setStatusCode($status = 400)
    {
        $this->httpStatus = $status;
    }

    protected function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;
    }

    protected function getExtraData()
    {
        return $this->extraData;
    }

    public function toJson()
    {
        return response()->json(
            [
                'status' => 'error',
                'data' =>[
                    'type' => 'error',
                    'code' => $this->getStatusCode(),
                    'name' => $this->getErrorName(),
                    'description' => $this->getMessage(),
                    ]
                ],
            Response::HTTP_BAD_REQUEST);

    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
