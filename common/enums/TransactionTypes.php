<?php

namespace common\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class TransactionTypes
 * @package common\enums
 */
class TransactionTypes extends BaseEnum
{
    const DEBIT = 1;
    const CREDIT = 2;
    const TRANSFER = 3;
    const INIT = 4;
    const REIMBURSEMENT = 5;

    /**
     * @var array
     */
    public static $list = [
        self::DEBIT => 'Приход',
        self::CREDIT => 'Расход',
        self::TRANSFER => 'Перемещение',
        self::INIT => 'Инициализация',
        self::REIMBURSEMENT => 'Возмещение расхода',
    ];

    /**
     * @var array
     */
    public static $categoryTypesList = [
        self::DEBIT => 'Приход',
        self::CREDIT => 'Расход',
        self::REIMBURSEMENT => 'Возмещение расхода',
    ];
}