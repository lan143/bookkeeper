<?php

namespace frontend\modules\bookkeeping\services;

use bulldozer\App;
use common\ar\Transaction;
use common\enums\BillTypes;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\forms\BillForm;
use frontend\modules\bookkeeping\forms\BillFormInterface;
use frontend\modules\bookkeeping\forms\CardForm;
use frontend\modules\bookkeeping\forms\CreditBillForm;
use frontend\modules\bookkeeping\forms\CreditCardForm;
use yii\base\Exception;

/**
 * Class BillService
 * @package frontend\modules\bookkeeping\services
 */
class BillService
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * BillService constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param int $type
     * @param Bill|null $bill
     * @return BillFormInterface
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     */
    public function getForm(int $type, ?Bill $bill = null): BillFormInterface
    {
        if ($type == BillTypes::CASH) {
            $class = BillForm::class;
        } elseif ($type == BillTypes::CARD) {
            $class = CardForm::class;
        } elseif ($type == BillTypes::CREDIT_CARD) {
            $class = CreditCardForm::class;
        } elseif ($type == BillTypes::CREDIT) {
            $class = CreditBillForm::class;
        } else {
            throw new Exception('Unsupported type');
        }

        /** @var BillForm $form */
        $form = App::createObject([
            'class' => $class,
        ]);

        if ($bill) {
            $form->setAttributes($bill->getAttributes($form->getSavedAttributes()));
            $form->setAttributes($bill->params);
        }

        return $form;
    }

    /**
     * @param int $type
     * @param BillFormInterface $form
     * @param Bill|null $bill
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function saveBill(int $type, BillFormInterface $form, ?Bill $bill = null): void
    {
        if ($bill === null) {
            $bill = App::createObject([
                'class' => Bill::class,
            ]);
        }

        $bill->type = $type;
        $bill->setAttributes($form->getAttributes($form->getSavedAttributes()));
        $bill->save();

        if (in_array($type, [BillTypes::CASH, BillTypes::CARD, BillTypes::CREDIT_CARD])) {
            /** @var BillForm $form */
            if ($form->initSum) {
                $this->transactionService->createTransaction(TransactionTypes::INIT, $form->initSum, 'Начальная сумма',
                    null, $bill);
            }
        }

        if ($type == BillTypes::CREDIT) {
            /** @var CreditBillForm $form */
            $params = [
                'type' => $form->type,
                'date_of_issue' => $form->date_of_issue,
                'pay_up_to' => $form->pay_up_to,
                'sum' => $form->sum,
                'additional_payments' => $form->additional_payments > 0 ? $form->additional_payments : 0,
                'term' => $form->term,
                'percents' => $form->percents,
                'month_error' => $form->month_error > 0 ? $form->month_error : 0,
                'document_no' => $form->document_no,
            ];
            $bill->params = $params;
            $bill->save();
        } elseif ($type == BillTypes::CREDIT_CARD) {
            /** @var CreditCardForm $form */
            $params = [
                'limit' => $form->limit,
                'percent' => $form->percent,
                'card_no' => $form->card_no,
            ];
            $bill->params = $params;
            $bill->save();
        } elseif ($type == BillTypes::CARD) {
            /** @var CardForm $form */
            $params = [
                'card_no' => $form->card_no,
            ];
            $bill->params = $params;
            $bill->save();
        }
    }
}