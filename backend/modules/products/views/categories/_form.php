<?php

use backend\widgets\MultipleFieldWidget;
use bulldozer\App;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \backend\modules\products\forms\CategoryForm $categoryForm
 * @var boolean $isNewRecord
 */

?>
<?php $form = ActiveForm::begin(); ?>

<?php if ($categoryForm->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= $form->errorSummary($categoryForm) ?>
    </div>
<?php endif ?>

<?= $form->field($categoryForm, 'name')->textInput() ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
