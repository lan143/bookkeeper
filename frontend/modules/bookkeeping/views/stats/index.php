<?php

/**
 * @var \yii\web\View $this
 * @var array $data
 * @var \common\components\report\Interval[] $intervals
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
            text: 'Доходы и расходы'
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

$this->registerJs($script, View::POS_READY);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = 'Статистика';

?>
<script>
    labels = [
        "<?= implode('", "', $intervals) ?>"
    ];
    datasets = [
        {
            name: 'Доход',
            data: [
                <?= implode(', ', ArrayHelper::getColumn($data[TransactionTypes::DEBIT]['intervals'], 'sum')) ?>
            ]
        },
        {
            name: 'Расход',
            data: [
                <?= implode(', ', ArrayHelper::getColumn($data[TransactionTypes::CREDIT]['intervals'], 'sum')) ?>
            ]
        }
    ];
</script>

<ul class="nav nav-tabs">
    <li role="presentation" class="active">
        <a href="<?= Url::to(['stats/index']) ?>">Доход и расход</a>
    </li>

    <li role="presentation">
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
        <div id="main" width="100%"></div>
    </div>
</div>