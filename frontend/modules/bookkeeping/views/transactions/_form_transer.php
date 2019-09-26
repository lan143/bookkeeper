<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\TransactionTransferForm $transactionForm
 * @var boolean $isNewRecord
 * @var Bill $bill
 */

use frontend\modules\bookkeeping\ar\Bill;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($transactionForm, 'date')->textInput() ?>

<?= $form->field($transactionForm, 'dest_bill')->dropDownList(ArrayHelper::map(Bill::findWithUser()->andWhere(['!=', 'id', $bill->id])->all(), 'id', 'name'), [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($transactionForm, 'sum')->textInput(['type' => 'number', 'min' => '0.01', 'step' => '0.01']) ?>

<?= $form->field($transactionForm, 'comment')->textarea() ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
