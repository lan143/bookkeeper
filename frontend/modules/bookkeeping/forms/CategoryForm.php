<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\enums\TransactionTypes;

/**
 * Class CategoryForm
 * @package frontend\modules\bookkeeping\forms
 */
class CategoryForm extends Form
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $transaction_type;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],

            ['transaction_type', 'required'],
            ['transaction_type', 'in', 'range' => [TransactionTypes::CREDIT, TransactionTypes::DEBIT]],
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'name',
            'transaction_type',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'transaction_type' => 'Тип транзакции',
        ];
    }
}