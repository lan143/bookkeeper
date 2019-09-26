<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\ar\Currency;

/**
 * Class CardForm
 * @package frontend\modules\bookkeeping\forms
 */
class CardForm extends Form implements BillFormInterface
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
    public $card_no;

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

            ['card_no', 'required'],
            ['card_no', 'integer'],
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
            'card_no' => 'Номер карты',
        ];
    }
}