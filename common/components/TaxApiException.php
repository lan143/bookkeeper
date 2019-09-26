<?php

namespace common\components;

use Throwable;
use yii\base\Exception;

class TaxApiException extends Exception
{
    /**
     * @var int
     */
    public $status;

    /**
     * TaxApiException constructor.
     * @param int $status
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(int $status, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->status = $status;
    }
}