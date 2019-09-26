<?php

namespace frontend\modules\bookkeeping\services;

use bulldozer\App;
use common\ar\Transaction;
use common\enums\TransactionTypes;
use DateTime;
use frontend\modules\bookkeeping\ar\Bill;
use frontend\modules\bookkeeping\forms\TransactionDebitCreditForm;
use frontend\modules\bookkeeping\forms\TransactionFormInterface;
use frontend\modules\bookkeeping\forms\TransactionReimbursementForm;
use frontend\modules\bookkeeping\forms\TransactionTransferForm;
use yii\base\Exception;

/**
 * Class TransactionService
 * @package frontend\modules\bookkeeping\services
 */
class TransactionService
{
    /**
     * @param int $type
     * @param Transaction|null $transaction
     * @return TransactionDebitCreditForm
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     */
    public function getForm(int $type, ?Transaction $transaction = null): TransactionFormInterface
    {
        $class = null;

        if (in_array($type, [TransactionTypes::DEBIT, TransactionTypes::CREDIT])) {
            $class = TransactionDebitCreditForm::class;
        } elseif ($type == TransactionTypes::REIMBURSEMENT) {
            $class = TransactionReimbursementForm::class;
        } elseif ($type == TransactionTypes::TRANSFER) {
            $class = TransactionTransferForm::class;
        } else {
            throw new Exception('Unsupported type');
        }

        /** @var TransactionDebitCreditForm $form */
        $form = App::createObject([
            'class' => $class,
        ]);

        if ($transaction) {
            $form->setAttributes($transaction->getAttributes($form->getSavedAttributes()));
        }

        return $form;
    }

    /**
     * @param int $type
     * @param TransactionFormInterface $form
     * @param Bill $bill
     * @param Transaction|null $transaction
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function save(int $type, TransactionFormInterface $form, Bill $bill, ?Transaction $transaction = null): void
    {
        $dbTransaction = App::$app->db->beginTransaction();

        if ($transaction === null) {
            $transaction = App::createObject([
                'class' => Transaction::class,
            ]);
        }

        $transaction->type = $type;
        $transaction->setAttributes($form->getAttributes($form->getSavedAttributes()));
        $transaction->date = (new DateTime($form->date))->getTimestamp();

        if ($transaction->isNewRecord) {
            if ($transaction->type == TransactionTypes::DEBIT) {
                $transaction->destination_bill_id = $bill->id;
                $this->updateBillSum($bill, $transaction);
            } elseif ($transaction->type == TransactionTypes::REIMBURSEMENT) {
                $transaction->destination_bill_id = $bill->id;
                $this->updateBillSum($bill, $transaction);
            } elseif ($transaction->type == TransactionTypes::CREDIT) {
                $transaction->source_bill_id = $bill->id;
                $this->updateBillSum($bill, $transaction);
            } elseif ($transaction->type == TransactionTypes::TRANSFER) {
                /** @var TransactionTransferForm $form */
                $transaction->source_bill_id = $bill->id;
                $transaction->destination_bill_id = $form->dest_bill;

                $destBill = Bill::findOne($form->dest_bill);
                $this->updateBillSum($bill, $transaction);
                $this->updateBillSum($destBill, $transaction);
            } else {
                throw new Exception('Unsupported transaction type');
            }
        }

        $transaction->save();

        $dbTransaction->commit();
    }

    /**
     * @param int $type
     * @param float $sum
     * @param string $comment
     * @param Bill|null $sourceBill
     * @param Bill|null $destinationBill
     * @return Transaction
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     */
    public function createTransaction(int $type, float $sum, string $comment, ?Bill $sourceBill = null, ?Bill $destinationBill = null): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = App::createObject([
            'class' => Transaction::class,
            'type' => $type,
            'sum' => $sum,
            'comment' => $comment,
            'source_bill_id' => $sourceBill ? $sourceBill->id : null,
            'destination_bill_id' => $destinationBill ? $destinationBill->id : null,
        ]);
        $transaction->save();

        if ($sourceBill) {
            $this->updateBillSum($sourceBill, $transaction);
        }

        if ($destinationBill) {
            $this->updateBillSum($destinationBill, $transaction);
        }

        return $transaction;
    }

    /**
     * @param Bill $bill
     * @param Transaction $transaction
     * @throws Exception
     */
    public function updateBillSum(Bill $bill, Transaction $transaction): void
    {
        if ($transaction->type == TransactionTypes::CREDIT) {
            if ($transaction->source_bill_id == $bill->id) {
                $bill->sum -= $transaction->sum;
            }
        } elseif ($transaction->type == TransactionTypes::DEBIT) {
            if ($transaction->destination_bill_id == $bill->id) {
                $bill->sum += $transaction->sum;
            }
        } elseif ($transaction->type == TransactionTypes::REIMBURSEMENT) {
            if ($transaction->destination_bill_id == $bill->id) {
                $bill->sum += $transaction->sum;
            }
        } elseif ($transaction->type == TransactionTypes::TRANSFER) {
            if ($transaction->source_bill_id == $bill->id) {
                $bill->sum -= $transaction->sum;
            } elseif ($transaction->destination_bill_id == $bill->id) {
                $bill->sum += $transaction->sum;
            } else {
                throw new Exception('Unexpected error occurred');
            }
        } elseif ($transaction->type == TransactionTypes::INIT) {
            $bill->sum += $transaction->sum;
        } else {
            throw new Exception('Unsupported type');
        }

        $bill->save();
    }
}