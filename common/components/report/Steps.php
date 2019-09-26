<?php

namespace common\components\report;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class Steps
 * @package common\components\report
 */
class Steps extends BaseEnum
{
    const DAY = 1;
    const WEEK = 2;
    const MONTH = 3;
    const QUART = 4;
    const YEAR = 5;

    /**
     * @var array
     */
    public static $list = [
        self::DAY => 'day',
        self::WEEK => 'week',
        self::MONTH => 'month',
        self::QUART => 'quart',
        self::YEAR => 'year',
    ];

    /**
     * @var string
     */
    public static $messageCategory = 'reports';
}