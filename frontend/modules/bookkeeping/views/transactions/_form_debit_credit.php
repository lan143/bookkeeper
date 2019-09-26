<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\TransactionDebitCreditForm $transactionForm
 * @var boolean $isNewRecord
 * @var array $categories
 */

use common\enums\TransactionTypes;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($transactionForm, 'date')->textInput() ?>

<?= $form->field($transactionForm, 'category_id')->dropDownList($categories, [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($transactionForm, 'sum')->textInput(['type' => 'number', 'min' => '0.01', 'step' => '0.01']) ?>

<?= $form->field($transactionForm, 'comment')->textarea() ?>

<?= $form->field($transactionForm, 'check_id', ['enableClientValidation' => false])->hiddenInput()->label(false) ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
