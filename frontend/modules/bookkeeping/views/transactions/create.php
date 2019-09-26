<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\TransactionDebitCreditForm $transactionForm
 * @var array $categories
 * @var int $type
 * @var \frontend\modules\bookkeeping\ar\Bill $bill
 */

use common\enums\TransactionTypes;

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['/bookkeeping/bills/index']];
$this->params['breadcrumbs'][] = ['label' => $bill->name, 'url' => ['/bookkeeping/bills/view', 'id' => $bill->id]];
$this->params['breadcrumbs'][] = 'Новая запись';

?>
<?php if (in_array($type, [TransactionTypes::CREDIT, TransactionTypes::DEBIT])): ?>
    <?= $this->render('_form_debit_credit', ['transactionForm' => $transactionForm, 'isNewRecord' => true, 'categories' => $categories]) ?>
<?php elseif ($type == TransactionTypes::REIMBURSEMENT): ?>
    <?= $this->render('_form_reimbursement', ['transactionForm' => $transactionForm, 'isNewRecord' => true]) ?>
<?php elseif ($type == TransactionTypes::TRANSFER): ?>
    <?= $this->render('_form_transer', ['isNewRecord' => true, 'transactionForm' => $transactionForm, 'bill' => $bill]) ?>
<?php endif ?>