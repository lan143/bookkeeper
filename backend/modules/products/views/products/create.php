<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \backend\modules\products\forms\ProductForm $productForm
 * @var array $categories
 */

$this->title = 'Новый товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
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
                    'productForm' => $productForm,
                    'categories' => $categories,
                    'isNewRecord' => true,
                ]) ?>
            </div>
        </section>
    </div>
</div>
