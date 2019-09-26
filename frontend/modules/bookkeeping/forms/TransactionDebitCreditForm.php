<?php

namespace frontend\modules\bookkeeping\forms;

use bulldozer\base\Form;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\ar\Category;
use frontend\modules\bookkeeping\ar\Check;

/**
 * Class TransactionDebitCreditForm
 * @package frontend\modules\bookkeeping\forms
 */
class TransactionDebitCreditForm extends Form implements TransactionFormInterface
{
    /**
     * @var int
     */
    public $category_id;

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
     * @var int
     */
    public $check_id;

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
            ['category_id', 'required'],
            ['category_id', 'in', 'range' => Category::findWithUser()->asArray()->select(['id'])->column()],

            ['sum', 'required'],
            ['sum', 'number', 'min' => 0.01],
            ['sum', 'validateSum'],

            ['comment', 'string'],

            ['date', 'required'],
            ['date', 'datetime', 'format' => 'php:d.m.Y H:i:s'],

            ['check_id', 'in', 'range' => Check::findWithUser()->select(['id'])->asArray()->column()],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateSum(string $attribute): void
    {
        if ($this->type == TransactionTypes::CREDIT && $this->bill->null_validation) {
            if ($this->bill->sum < $this->sum) {
                $this->addError($attribute, 'Сумма меньше имеющейся на кошельке');
            }
        }
    }

    /**
     * @return array
     */
    public function getSavedAttributes(): array
    {
        return [
            'category_id',
            'sum',
            'comment',
            'check_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Категория',
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