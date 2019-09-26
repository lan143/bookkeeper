<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\BillForm $billForm
 * @var \frontend\modules\bookkeeping\ar\Bill $bill
 * @var array $currencies
 */

use common\enums\BillTypes;

$this->title = 'Редактирование: ' . $bill->name;
if ($bill->type == BillTypes::CREDIT) {
    $this->params['breadcrumbs'][] = ['label' => 'Кредиты', 'url' => ['credits']];
} elseif ($bill->type == BillTypes::CONTRIBUTION) {
    $this->params['breadcrumbs'][] = ['label' => 'Вклады', 'url' => ['contributions']];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['index']];
}

$this->params['breadcrumbs'][] = ['label' => $bill->name, 'url' => ['view', 'id' => $bill->id]];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<?php if ($bill->type == BillTypes::CASH): ?>
    <?= $this->render('_form', ['billForm' => $billForm, 'isNewRecord' => false, 'currencies' => $currencies]) ?>
<?php elseif ($bill->type == BillTypes::CARD): ?>
    <?= $this->render('_card_form', ['billForm' => $billForm, 'isNewRecord' => false, 'currencies' => $currencies]) ?>
<?php elseif ($bill->type == BillTypes::CREDIT): ?>
    <?= $this->render('_credit_form', ['billForm' => $billForm, 'isNewRecord' => false, 'currencies' => $currencies]) ?>
<?php elseif ($bill->type == BillTypes::CREDIT_CARD): ?>
    <?= $this->render('_credit-card_form', ['billForm' => $billForm, 'isNewRecord' => false, 'currencies' => $currencies]) ?>
<?php endif ?>