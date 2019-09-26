<?php

/**
 * @var \yii\web\View $this
 * @var array $dataSets
 * @var \common\components\report\Interval[] $intervals
 */

use common\enums\TransactionTypes;
use frontend\assets\HighchartsAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

HighchartsAsset::register($this);

$script = '';

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
    <?php foreach ($intervals as $key => $interval): ?>
        datasets<?= $key ?> = <?= json_encode($dataSets[$key]) ?>;
    <?php endforeach ?>
</script>

<ul class="nav nav-tabs">
    <li role="presentation">
        <a href="<?= Url::to(['stats/index']) ?>">Доход и расход</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/categories']) ?>">По категориям</a>
    </li>

    <li role="presentation">
        <a href="<?= Url::to(['stats/check-categories']) ?>">По категориям товаров</a>
    </li>

    <li role="presentation" class="active">
        <a href="<?= Url::to(['stats/check-products']) ?>">По товарам</a>
    </li>
</ul>

<div class="row">
    <?php foreach ($intervals as $key => $interval): ?>
        <div class="col-md-6">
            <div id="pie-<?= $key ?>" width="100%"></div>
        </div>
    <?php endforeach ?>
</div>