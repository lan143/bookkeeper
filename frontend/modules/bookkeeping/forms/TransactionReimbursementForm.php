<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\ar\Check;

/**
 * Class TransactionReimbursementForm
 * @package frontend\modules\bookkeeping\forms
 */
class TransactionReimbursementForm extends Form implements TransactionFormInterface
{
    /**
     * @var float
     */
    public $sum;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $date;

    /**
     * @var Bill
     */
    protected $bill;

    /**
     * @var int
     */
    protected $type;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        $this->date = date('d.m.Y H:i:s');
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['sum', 'required'],
            ['sum', 'number', 'min' => 0.01],

            ['comment', 'string'],

            ['date', 'required'],
            ['date', 'datetime', 'format' => 'php:d.m.Y H:i:s'],
        ];
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'sum',
            'comment',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'sum' => 'Сумма',
            'comment' => 'Комментарий',
            'date' => 'Дата',
        ];
    }

    /**
     * @param Bill $bill
     */
    public function setBill(Bill $bill): void
    {
        $this->bill = $bill;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }
}