<?php

namespace common\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class CreditTypes
 * @package common\enums
 */
class CreditTypes extends BaseEnum
{
    const DIFFERENTIAL = 1;
    const ANNUITY = 2;

    /**
     * @var array
     */
    public static $list = [
        self::DIFFERENTIAL => 'Дифференциальный',
        self::ANNUITY => 'Аннуитет',
    ];
}