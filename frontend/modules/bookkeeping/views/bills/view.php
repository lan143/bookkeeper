<?php

/**
 * @var \yii\web\View $this
 * @var Bill $bill
 * @var \yii\data\ActiveDataProvider $transactionsDataProvider
 */

use common\ar\Transaction;
use common\enums\TransactionTypes;
use frontend\modules\bookkeeping\ar\Bill;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $bill->name;
$this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $bill->name;

?>

<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['bills/update', 'id' => $bill->id]) ?>" class="btn btn-primary">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            Редактировать
        </a>

        <ul>
            <li>Название: <?= $bill->name ?></li>
            <li>Тип: <?= $bill->getTypeName() ?></li>
            <li>Сумма: <?= number_format($bill->sum, 2, '.', ' ') ?> <?= $bill->currency->name ?></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div style="margin-bottom: 20px;" class="clearfix">
            <div class="dropdown" style="float: left;">
                <button class="btn btn-default btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    <span class="caret"></span>
                </button>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li>
                        <a href="<?= Url::to(['transactions/create', 'billId' => $bill->id, 'type' => TransactionTypes::DEBIT]) ?>">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Добавить доход
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['transactions/create', 'billId' => $bill->id, 'type' => TransactionTypes::CREDIT]) ?>">
                            <i class="fa fa-minus" aria-hidden="true"></i>
                            Добавить расход
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['transactions/create', 'billId' => $bill->id, 'type' => TransactionTypes::TRANSFER]) ?>">
                            <i class="fa fa-arrows-h" aria-hidden="true"></i>
                            Переместить средства
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['transactions/create', 'billId' => $bill->id, 'type' => TransactionTypes::REIMBURSEMENT]) ?>">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            Возвращение средств
                        </a>
                    </li>
                </ul>
            </div>

            <a href="<?= Url::to(['transactions/scan', 'billId' => $bill->id]) ?>" class="btn btn-danger btn-lg" style="margin-left: 10px;">
                <i class="fa fa-camera" aria-hidden="true"></i>
            </a>
        </div>

        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $transactionsDataProvider,
                'columns' => [
                    [
                        'label' => 'Дата',
                        'attribute' => 'date',
                        'format' => 'datetime',
                    ],
                    [
                        'label' => 'Тип',
                        'content' => function(Transaction $transaction) use($bill) {
                            if (in_array($transaction->type, [
                                TransactionTypes::CREDIT,
                                TransactionTypes::DEBIT,
                                TransactionTypes::INIT,
                                TransactionTypes::REIMBURSEMENT]
                            )) {
                                return $transaction->getTypeName();
                            } elseif ($transaction->type == TransactionTypes::TRANSFER) {
                                $result = 'Перемещение ' . Html::tag('br');

                                if ($transaction->source_bill_id == $bill->id) {
                                    $result .= '(отсюда -> ' . $transaction->destinationBill->name . ')';
                                } elseif ($transaction->destination_bill_id == $bill->id) {
                                    $result .= '(' . $transaction->sourceBill->name . ' -> сюда)';
                                }

                                return $result;
                            }
                        },
                    ],
                    [
                        'label' => 'Категория',
                        'attribute' => 'category.name',
                    ],
                    [
                        'label' => 'Сумма',
                        'content' => function(Transaction $transaction) {
                            return number_format($transaction->sum, '2', '.', ' ');
                        }
                    ],
                    [
                        'label' => 'Комментарий',
                        'content' => function (Transaction $transaction) {
                            $result = $transaction->comment;

                            if ($transaction->check && strlen($transaction->check->shop_name) > 0) {
                                if (strlen($result) > 0) {
                                    $result .= Html::tag('br') . Html::tag('br');
                                }

                                $result .= 'Магазин: ' . $transaction->check->shop_name . Html::tag('br');

                                if (strlen($transaction->check->shop_address) > 0) {
                                    $result .= 'Адрес: ' . $transaction->check->shop_address . Html::tag('br');
                                }
                            }

                            return $result;
                        },
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view}',
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action == 'update') {
                                return Url::to(['transactions/update', 'id' => $model->id]);
                            } elseif ($action == 'delete') {
                                return Url::to(['transactions/delete', 'id' => $model->id]);
                            } elseif ($action == 'view') {
                                return Url::to(['transactions/view', 'id' => $model->id]);
                            }
                        },
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
