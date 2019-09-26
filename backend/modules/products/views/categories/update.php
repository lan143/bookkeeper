<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \backend\modules\products\forms\CategoryForm $categoryForm
 * @var \common\ar\ProductCategory $category
 * @var array $categories
 */

$this->title = 'Редактирование категории: ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
$this->params['breadcrumbs'][] = 'Редактирование';
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
                    'isNewRecord' => false,
                ]) ?>
            </div>
        </section>
    </div>
</div>
