<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\ar\Currency;
use common\enums\BillTypes;

/**
 * Class BillForm
 * @package frontend\modules\bookkeeping\forms
 */
class BillForm extends Form implements BillFormInterface
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
            'null_validation' => 'Валидация суммы включена',
        ];
    }
}