<?php

/**
 * @var \yii\web\View $this
 * @var array $data
 * @var \common\components\report\Interval[] $intervals
 * @var \common\ar\ProductCategory[] $categories
 */

use common\enums\TransactionTypes;
use frontend\assets\HighchartsAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

HighchartsAsset::register($this);

$script = <<< JS
    Highcharts.chart('main', {
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Категории товаров'
        },
        xAxis: {
            categories: labels
        },
        yAxis: {
            title: {
                text: 'Руб.'
            }
        },
        series: datasets
    });
JS;

foreach ($intervals as $key => $interval) {
    $name = $interval->name;
    $script .= <<< JS
    Highcharts.chart('pie-$key', {
        chart: {
            type: 'pie',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },
        title: {
            text: '$name'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: datasets$key
    });
JS;
}

$this->registerJs($script, View::POS_READY);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = 'Статистика';

?>
<script>
    labels = [
        "<?= implode('", "', $intervals) ?>"
    ];
    datasets = [
        <?php foreach ($categories as $category): ?>
        <?php
        $dataset = [];

        foreach ($data['intervals'] as $datum) {
            $dataset[] = $datum['categories'][$category->id]['sum'];
        }
        ?>
        {
            name: '<?= $category->name ?>',
            data: [
                <?= implode(', ', $dataset) ?>
            ]
        },
        <?php endforeach ?>
    ];
    <?php foreach ($intervals as $key => $interval): ?>
        datasets<?= $key ?> = [{
            name: 'Категории',
            colorByPoint: true,
            data: [
                <?php foreach ($categories as $category): ?>
                {
                    name: '<?= $category->name ?>',
                    y: <?= $data['intervals'][$key]['categories'][$category->id]['sum'] ?>
                },
                <?php endforeach ?>
            ],
        }];
    <?php endforeach ?>
</script>

<ul class="nav nav-tabs">
    <li role="presentation">
        <a href="<?= Url::to(['stats/index']) ?>">Доход и расход</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/categories']) ?>">По категориям</a>
    </li>

    <li role="presentation" class="active">
        <a href="<?= Url::to(['stats/check-categories']) ?>">По категориям товаров</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/check-products']) ?>">По товарам</a>
    </li>
</ul>

<div class="row">
    <div class="col-md-12">
        <div id="main" width="100%"></div>
    </div>
</div>

<div class="row">
    <?php foreach ($intervals as $key => $interval): ?>
        <div class="col-md-6">
            <div id="pie-<?= $key ?>" width="100%"></div>
        </div>
    <?php endforeach ?>
</div>