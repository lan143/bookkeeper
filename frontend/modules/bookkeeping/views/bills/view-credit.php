<?php

/**
 * @var \yii\web\View $this
 * @var Bill $bill
 * @var array $scheduleOfPayments
 * @var array $stats
 */

use common\ar\Transaction;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = $bill->name;
$this->params['breadcrumbs'][] = ['label' => 'Кредиты', 'url' => ['credits']];
$this->params['breadcrumbs'][] = $bill->name;

?>
<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['bills/update', 'id' => $bill->id]) ?>" class="btn btn-primary">Редактирование кредита</a>

        <?php if (!empty($bill->params['document_no'])): ?>
            <a href="#" data-toggle="modal" data-target="#documentNoModal" class="btn btn-primary">Показать номер договора</a>

            <div class="modal fade" id="documentNoModal" tabindex="-1" role="dialog" aria-labelledby="documentNoModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="documentNoModalLabel">Номер договора</h4>
                        </div>
                        <div class="modal-body" style="text-align: center;">
                            Номер договора: <?= $bill->params['document_no'] ?><br /><br />
                            <?php
                            $generator = new BarcodeGeneratorPNG();
                            $barCode = $generator->getBarcode($bill->params['document_no'], $generator::TYPE_CODE_128, 4, 150);
                            ?>
                            <img src="data:image/png;base64,<?= base64_encode($barCode) ?>" style="max-width: 100%;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        
        <h3>Информация о кредите</h3>
        <ul>
            <li>Сумма кредита: <?= number_format($bill->params['sum'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Дата оформления договора: <?= $bill->params['date_of_issue'] ?></li>
            <li>Срок: <?= $bill->params['term'] ?> месяцев</li>
            <li>Процентная ставка: <?= $bill->params['percents'] ?>%</li>
            <li>Дополнительные платежи: <?= number_format($bill->params['additional_payments'], 2, '.', ' ') ?> <?= $bill->currency->name ?>/месяц</li>
        </ul>

        <ul>
            <li>Произведено платежей: <?= $stats['payed_count'] ?></li>
            <li>Выплачено: <?= number_format($stats['payed_sum'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Выплачено процентов: <?= number_format($stats['payed_percents'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Выплачено основного долга: <?= number_format($stats['payed_main_dept'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
        </ul>

        <ul>
            <li>Осталось платежей: <?= $stats['remains_count'] ?></li>
            <li>Осталось платить: <?= number_format($stats['remains_sum'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Осталось платить процентов: <?= number_format($stats['remains_percents'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Осталось платить основного долга: <?= number_format($stats['remains_main_dept'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
        </ul>

        <ul>
            <li>Сумма процентов: <?= number_format($stats['payed_percents'] + $stats['remains_percents'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
            <li>Сумма кредита: <?= number_format($bill->params['sum'] + $stats['payed_percents'] + $stats['remains_percents'], 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h3>График платежей</h3>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Дата платежа</th>
                    <th>Сумма основного долга, подлежащая уплате</th>
                    <th>Сумма процентов, подлежащая уплате</th>
                    <th>Ежемесячный платеж за кредит</th>
                    <th>Дополнительные платежи</th>
                    <th>Взнос всего</th>
                    <th>Остаток задолженности по кредиту</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($scheduleOfPayments as $date => $payment): ?>
                    <tr>
                        <td><?= $date ?></td>
                        <td><?= number_format($payment['main_debt'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                        <td><?= number_format($payment['percents_debt'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                        <td><?= number_format($payment['sep'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                        <td><?= number_format($payment['add'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                        <td><?= number_format($payment['full'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                        <td><?= number_format($payment['creditSum'], 2, '.', ' ') ?> <?= $bill->currency->name ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>