<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\CreditBillForm $billForm
 * @var array $currencies
 * @var boolean $isNewRecord
 */

use common\enums\BillTypes;
use common\enums\CreditTypes;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($billForm, 'name')->textInput() ?>

<?= $form->field($billForm, 'document_no')->textInput() ?>

<?= $form->field($billForm, 'currency_id')->dropDownList($currencies, [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($billForm, 'type')->dropDownList(CreditTypes::listData(), [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($billForm, 'date_of_issue')->textInput() ?>

<?= $form->field($billForm, 'pay_up_to')->textInput() ?>

<?= $form->field($billForm, 'sum')->textInput(['type' => 'number', 'min' => 0.01, 'step' => 0.01]) ?>

<?= $form->field($billForm, 'additional_payments')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01]) ?>

<?= $form->field($billForm, 'term')->textInput(['type' => 'number', 'min' => 1]) ?>

<?= $form->field($billForm, 'percents')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.001]) ?>

<?= $form->field($billForm, 'month_error')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01]) ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
