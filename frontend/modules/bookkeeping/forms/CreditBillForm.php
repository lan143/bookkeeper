<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\db\ActiveRecord;
use common\ar\Currency;
use common\enums\CreditTypes;

/**
 * Class CreditBillForm
 * @package frontend\modules\bookkeeping\forms
 */
class CreditBillForm extends ActiveRecord implements BillFormInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $currency_id;

    /**
     * @var int
     */
    public $type;

    /**
     * @var string
     */
    public $date_of_issue;

    /**
     * @var string
     */
    public $pay_up_to;

    /**
     * @var float
     */
    public $sum;

    /**
     * @var float
     */
    public $additional_payments;

    /**
     * @var int
     */
    public $term;

    /**
     * @var float
     */
    public $percents;

    /**
     * @var float
     */
    public $month_error;

    /**
     * @var string
     */
    public $document_no;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],

            ['currency_id', 'required'],
            ['currency_id', 'in', 'range' => Currency::find()->asArray()->select(['id'])->column()],

            ['type', 'required'],
            ['type', 'in', 'range' => array_keys(CreditTypes::listData())],

            ['date_of_issue', 'required'],
            ['date_of_issue', 'date', 'format' => 'php:d.m.Y'],

            ['pay_up_to', 'required'],
            ['pay_up_to', 'integer', 'min' => 1, 'max' => 31],

            ['sum', 'required'],
            ['sum', 'number', 'min' => 0.01],

            ['additional_payments', 'number', 'min' => 0],

            ['term', 'required'],
            ['term', 'integer', 'min' => 1],

            ['percents', 'required'],
            ['percents', 'number', 'min' => 0],

            ['month_error', 'number', 'min' => 0],

            ['document_no', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'currency_id' => 'Валюта кредита',
            'type' => 'Тип кредита',
            'date_of_issue' => 'Дата подписания договора',
            'pay_up_to' => 'Платеж вносить до',
            'sum' => 'Сумма кредита',
            'additional_payments' => 'Дополнительные ежемесячные платежи',
            'term' => 'Срок кредита',
            'percents' => 'Проценты',
            'month_error' => 'Ежемесячная погрешность',
            'document_no' => 'Номер договора или счета для оплаты',
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
            'currency_id',
            'sum',
        ];
    }
}