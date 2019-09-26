<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use frontend\modules\bookkeeping\ar\Bill;

/**
 * Class TransactionTransferForm
 * @package frontend\modules\bookkeeping\forms
 */
class TransactionTransferForm extends Form implements TransactionFormInterface
{
    /**
     * @var integer
     */
    public $dest_bill;

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
            ['dest_bill', 'required'],
            ['dest_bill', 'in', 'range' => Bill::findWithUser()->select(['id'])->asArray()->column()],

            ['sum', 'required'],
            ['sum', 'number', 'min' => 0.01],
            ['sum', 'validateSum'],

            ['comment', 'string'],

            ['date', 'required'],
            ['date', 'datetime', 'format' => 'php:d.m.Y H:i:s'],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateSum(string $attribute): void
    {
        if ($this->bill->sum < $this->sum) {
            $this->addError($attribute, 'Сумма меньше имеющейся на кошельке');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'dest_bill' => 'Кошелек назначения',
            'sum' => 'Сумма',
            'comment' => 'Комментарий',
            'date' => 'Дата',
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