<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\enums\BillTypes;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Категории';
$this->params['breadcrumbs'][] = 'Категории';

?>
<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['categories/create']) ?>" class="btn btn-primary">
            Добавить категорию
        </a>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'Название',
                    'attribute' => 'name',
                ],
                [
                    'label' => 'Тип',
                    'attribute' => 'transactionTypeName',
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{update} {delete}',
                ],
            ],
        ]); ?>
    </div>
</div>
