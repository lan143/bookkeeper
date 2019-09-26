<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \backend\modules\products\forms\CategoryForm $categoryForm
 */

$this->title = 'Новая категория';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                </div>

                <h2 class="panel-title"><?= Html::encode($this->title) ?></h2>
            </header>

            <div class="panel-body">
                <?= $this->render('_form', [
                    'categoryForm' => $categoryForm,
                    'isNewRecord' => true,
                ]) ?>
            </div>
        </section>
    </div>
</div>
