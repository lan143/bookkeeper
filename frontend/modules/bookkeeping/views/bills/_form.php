<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\BillForm $billForm
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

<?= $form->field($billForm, 'initSum')->textInput() ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
