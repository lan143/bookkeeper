<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\CategoryForm $categoryForm
 * @var boolean $isNewRecord
 */

use common\enums\BillTypes;
use common\enums\TransactionTypes;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($categoryForm, 'name')->textInput() ?>

<?= $form->field($categoryForm, 'transaction_type')->dropDownList(TransactionTypes::$categoryTypesList, [
    'prompt' => 'Не выбрано',
]) ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
