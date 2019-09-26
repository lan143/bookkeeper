<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\ar\Currency;

/**
 * Class CreditCardForm
 * @package frontend\modules\bookkeeping\forms
 */
class CreditCardForm extends Form implements BillFormInterface
{
    /**
     * @var int
     */
    public $currency_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $initSum;

    /**
     * @var float
     */
    public $limit;

    /**
     * @var float
     */
    public $percent;

    /**
     * @var int
     */
    public $card_no;

    /**
     * @var int
     */
    public $null_validation;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['currency_id', 'required'],
            ['currency_id', 'in', 'range' => Currency::find()->asArray()->select(['id'])->column()],

            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['initSum', 'number', 'min' => 0],

            ['limit', 'required'],
            ['limit', 'number', 'min' => 0.01],

            ['percent', 'required'],
            ['percent', 'number', 'min' => 0],

            ['card_no', 'required'],
            ['card_no', 'integer'],

            ['null_validation', 'boolean'],
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'currency_id',
            'name',
            'null_validation',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'currency_id' => 'Валюта',
            'name' => 'Название',
            'initSum' => 'Начальная сумма',
            'limit' => 'Лимит',
            'percent' => 'Проценты',
            'card_no' => 'Номер карты',
            'null_validation' => 'Валидация суммы включена',
        ];
    }
}