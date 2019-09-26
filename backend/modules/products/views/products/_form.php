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
 * @var \backend\modules\products\forms\ProductForm $productForm
 * @var array $categories
 * @var boolean $isNewRecord
 */

?>
<?php $form = ActiveForm::begin(); ?>

<?php if ($productForm->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= $form->errorSummary($productForm) ?>
    </div>
<?php endif ?>

<?= $form->field($productForm, 'name')->textInput() ?>

<?= $form->field($productForm, 'category_id')->dropDownList($categories, [
    'prompt' => 'Не выбрано',
]) ?>

<div class="form-group" style="margin-top: 10px;">
    <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Обновить',
        ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
