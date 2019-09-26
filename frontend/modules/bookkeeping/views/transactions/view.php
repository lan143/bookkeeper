<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\ar\Transaction $transaction
 * @var array $dataSets
 */

use common\enums\TransactionTypes;
use frontend\assets\HighchartsAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;

$this->title = 'Транзакция №' . $transaction->id;
$this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['/bookkeeping/bills']];
$this->params['breadcrumbs'][] = 'Транзакция №' . $transaction->id;

HighchartsAsset::register($this);

$script = <<< JS
    Highcharts.chart('categories-pie', {
        chart: {
            type: 'pie',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
        },
        title: {
            text: 'По категориям'
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
        series: dataSets
    });
JS;

if ($transaction->check) {
    $this->registerJs($script, View::POS_READY);
}

?>
<?php if ($transaction->check): ?>
    <script>
        dataSets = <?= json_encode($dataSets) ?>;
    </script>
<?php endif ?>
<div class="row">
    <div class="col-md-12">
        <ul>
            <li>Дата: <?= (new DateTime())->setTimestamp($transaction->date)->format('d.m.Y H:i:s') ?></li>
            <li>
                Тип:
                <?php if (in_array($transaction->type, [
                    TransactionTypes::CREDIT,
                    TransactionTypes::DEBIT,
                    TransactionTypes::INIT,
                    TransactionTypes::REIMBURSEMENT,
                ])): ?>
                    <?= $transaction->getTypeName() ?>
                <?php elseif ($transaction->type == TransactionTypes::TRANSFER): ?>
                    Перемещение
                    <?= $transaction->sourceBill->name ?> - <?= $transaction->destinationBill->name ?>
                <?php endif ?>
            </li>
            <?php if ($transaction->category): ?>
                <li>Категория: <?= $transaction->category->name ?></li>
            <?php endif ?>
            <li>Сумма: <?= number_format($transaction->sum, 2, '.', ' ') ?></li>
            <?php if (strlen($transaction->comment) > 0): ?>
                <li>Комментарий: <?= $transaction->comment ?></li>
            <?php endif ?>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($transaction->check): ?>
            <ul>
                <?php if (strlen($transaction->check->shop_name) > 0): ?>
                    <li>Магазин: <?= $transaction->check->shop_name ?></li>
                <?php endif ?>

                <?php if (strlen($transaction->check->shop_address)): ?>
                    <li>Адрес: <?= $transaction->check->shop_address ?></li>
                <?php endif ?>
            </ul>

            <table class="table">
                <caption>Товары</caption>

                <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                    <th>НДС</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($transaction->check->items as $item): ?>
                    <tr>
                        <td><?= $item->name ?></td>
                        <td><?= ArrayHelper::getValue($item, 'product.category.name', 'Не найдена') ?></td>
                        <td><?= $item->quantity ?></td>
                        <td><?= number_format($item->price, 2, '.', ' ') ?></td>
                        <td><?= number_format($item->sum, 2, '.', ' ') ?></td>
                        <td><?= number_format($item->nds10, 2, '.', ' ') ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

            <div id="categories-pie"></div>
        <?php endif ?>
    </div>
</div>
