<?php

namespace common\components\report;

use DateTime;
use yii\base\BaseObject;

/**
 * Class Interval
 * @package common\components\report
 */
class Interval extends BaseObject
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;

    /**
     * @return DateTime
     */
    public function getFromDateTime(): DateTime
    {
        return (new DateTime($this->from));
    }

    /**
     * @return DateTime
     */
    public function getToDateTime(): DateTime
    {
        return (new DateTime($this->to));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}