<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Категории';
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
                <p>
                    <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <div class="table-responsive">
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'label' => 'Название',
                                'attribute' => 'name',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>
