<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\BillForm $billForm
 * @var int $type
 * @var array $currencies
 */

use common\enums\BillTypes;

if ($type == BillTypes::CREDIT) {
    $this->params['breadcrumbs'][] = ['label' => 'Кредиты', 'url' => ['credits']];
    $this->params['breadcrumbs'][] = 'Новый кредит';
    $this->title = 'Новый кредит';
} elseif ($type == BillTypes::CONTRIBUTION) {
    $this->params['breadcrumbs'][] = ['label' => 'Вклады', 'url' => ['contributions']];
    $this->params['breadcrumbs'][] = 'Новый вклад';
    $this->title = 'Новый вклад';
} elseif ($type == BillTypes::CREDIT_CARD) {
    $this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['contributions']];
    $this->params['breadcrumbs'][] = 'Новая кредитная карта';
    $this->title = 'Новая кредитная карта';
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Новый кошелек';
    $this->title = 'Новый кошелек';
}

?>
<?php if ($type == BillTypes::CASH): ?>
    <?= $this->render('_form', ['billForm' => $billForm, 'isNewRecord' => true, 'currencies' => $currencies]) ?>
<?php elseif ($type == BillTypes::CARD): ?>
    <?= $this->render('_card_form', ['billForm' => $billForm, 'isNewRecord' => true, 'currencies' => $currencies]) ?>
<?php elseif ($type == BillTypes::CREDIT): ?>
    <?= $this->render('_credit_form', ['billForm' => $billForm, 'isNewRecord' => true, 'currencies' => $currencies]) ?>
<?php elseif ($type == BillTypes::CREDIT_CARD): ?>
    <?= $this->render('_credit-card_form', ['billForm' => $billForm, 'isNewRecord' => true, 'currencies' => $currencies]) ?>
<?php endif ?>