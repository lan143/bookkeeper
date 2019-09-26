<?php

/**
 * @var \yii\web\View $this
 * @var array $data
 * @var \common\components\report\Interval[] $intervals
 * @var array $creditCategories
 * @var array $debitCategories
 */

use common\enums\TransactionTypes;
use frontend\assets\HighchartsAsset;
use yii\helpers\Url;
use yii\web\View;

HighchartsAsset::register($this);

$script = <<< JS
    Highcharts.chart('debit-chart', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Доходы'
        },
        xAxis: {
            categories: labels
        },
        yAxis: {
            title: {
                text: 'Руб.'
            }
        },
        series: debitDatasets
    });

    Highcharts.chart('credit-chart', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Расходы'
        },
        xAxis: {
            categories: labels
        },
        yAxis: {
            title: {
                text: 'Руб.'
            }
        },
        series: creditDatasets
    });
JS;

$this->registerJs($script, View::POS_READY);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = 'Статистика';

?>
<script>
    labels = [
        "<?= implode('", "', $intervals) ?>"
    ];
    debitDatasets = [
        <?php foreach ($debitCategories as $id => $name): ?>
            <?php
            $dataset = [];

            foreach ($data[TransactionTypes::DEBIT]['intervals'] as $datum) {
                $dataset[] = $datum['categories'][$id]['sum'];
            }
            ?>
            {
                name: '<?= $name ?>',
                data: [
                    <?= implode(', ', $dataset) ?>
                ]
            },
        <?php endforeach ?>
    ];
    creditDatasets = [
        <?php foreach ($creditCategories as $id => $name): ?>
        <?php
        $dataset = [];

        foreach ($data[TransactionTypes::CREDIT]['intervals'] as $datum) {
            $dataset[] = $datum['categories'][$id]['sum'];
        }
        ?>
        {
            name: '<?= $name ?>',
            data: [
                <?= implode(', ', $dataset) ?>
            ]
        },
        <?php endforeach ?>
    ];
</script>

<ul class="nav nav-tabs">
    <li role="presentation">
        <a href="<?= Url::to(['stats/index']) ?>">Доход и расход</a>
    </li>

    <li role="presentation" class="active">
        <a href="<?= Url::to(['stats/categories']) ?>">По категориям</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/check-categories']) ?>">По категориям товаров</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/check-products']) ?>">По товарам</a>
    </li>
</ul>

<div class="row">
    <div class="col-md-12">
        <h1>Доход</h1>
        <div id="debit-chart" width="100%"></div>

        <h1>Расход</h1>
        <div id="credit-chart" width="100%"></div>
    </div>
</div>