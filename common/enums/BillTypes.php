<?php

namespace common\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class BillTypes
 * @package common\enums
 */
class BillTypes extends BaseEnum
{
    const CASH = 1;
    const CARD = 2;
    const CONTRIBUTION = 3;
    const CREDIT_CARD = 4;
    const CREDIT = 5;

    /**
     * @var array
     */
    public static $list = [
        self::CASH => 'Наличные',
        self::CARD => 'Дебетовая карта',
        self::CONTRIBUTION => 'Вклад',
        self::CREDIT_CARD => 'Кредитная карта',
        self::CREDIT => 'Кредит',
    ];
}