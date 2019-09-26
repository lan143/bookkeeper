<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\CreditCardForm $billForm
 * @var array $currencies
 * @var boolean $isNewRecord
 */

use common\enums\BillTypes;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($billForm, 'name')->textInput() ?>

<?= $form->field($billForm, 'currency_id')->dropDownList($currencies, [
    'prompt' => 'Не выбрано',
]) ?>

<?= $form->field($billForm, 'limit')->textInput(['type' => 'number', 'min' => 0.01, 'step' => 0.01]) ?>

<?= $form->field($billForm, 'percent')->textInput(['type' => 'number', 'min' => 0.01, 'step' => 0.01]) ?>

<?= $form->field($billForm, 'card_no')->textInput(['type' => 'number']) ?>

<?php if ($isNewRecord): ?>
    <?= $form->field($billForm, 'initSum')->textInput(['type' => 'number', 'step' => 0.01]) ?>
<?php endif ?>

<?= $form->field($billForm, 'null_validation')->checkbox() ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
