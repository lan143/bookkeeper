<?php

/**
 * @var \yii\web\View $this
 * @var \common\ar\Bill[] $bills
 */

use common\enums\BillTypes;
use yii\helpers\Url;

$this->title = 'Кредиты';
$this->params['breadcrumbs'][] = 'Кредиты';

?>
<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['bills/create', 'type' => BillTypes::CREDIT]) ?>" class="btn btn-primary">
            Добавить кредит
        </a>

        <?php if (count($bills) > 0): ?>
            <div class="row" style="margin-top: 20px;">
                <?php foreach ($bills as $bill): ?>
                    <div class="col-md-3" style="text-align: center">
                        <a href="<?= Url::to(['bills/view', 'id' => $bill->id]) ?>">
                            <div>
                                <?php if ($bill->type == BillTypes::CASH): ?>
                                    <i class="fa fa-money" style="font-size: 6em;" aria-hidden="true"></i>
                                <?php elseif ($bill->type == BillTypes::CARD): ?>
                                    <i class="fa fa-credit-card" style="font-size: 6em;" aria-hidden="true"></i>
                                <?php elseif ($bill->type == BillTypes::CREDIT_CARD): ?>
                                    <i class="fa fa-credit-card" style="font-size: 6em;" aria-hidden="true"></i>
                                <?php elseif ($bill->type == BillTypes::CONTRIBUTION): ?>
                                    <i class="fa fa-diamond" style="font-size: 6em;" aria-hidden="true"></i>
                                <?php elseif ($bill->type == BillTypes::CREDIT): ?>
                                    <i class="fa fa-university" style="font-size: 6em;" aria-hidden="true"></i>
                                <?php endif ?>
                            </div>

                            <h2><?= $bill->name ?></h2>
                        </a>

                        Сумма: <?= number_format($bill->sum, 2, '.', ' ') ?> <?= $bill->currency->name ?><br />
                        Срок: <?= $bill->params['term'] ?> месяцев<br />
                        Процентная ставка: <?= $bill->params['percents'] ?>%<br />
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <p>У вас пока что нет кредитов.</p>
        <?php endif ?>
    </div>
</div>
