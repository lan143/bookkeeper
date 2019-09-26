<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \backend\modules\products\forms\ProductForm $productForm
 * @var \common\ar\Product $product
 * @var array $categories
 */

$this->title = 'Редактирование товара: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $product->name;
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
                    'productForm' => $productForm,
                    'categories' => $categories,
                    'isNewRecord' => false,
                ]) ?>
            </div>
        </section>
    </div>
</div>
