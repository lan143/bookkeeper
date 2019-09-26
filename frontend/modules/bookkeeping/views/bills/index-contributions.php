<?php

/**
 * @var \yii\web\View $this
 * @var \common\ar\Bill[] $bills
 */

use common\enums\BillTypes;
use yii\helpers\Url;

$this->title = 'Вклады';
$this->params['breadcrumbs'][] = 'Вклады';

?>
<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['bills/create', 'type' => BillTypes::CONTRIBUTION]) ?>" class="btn btn-primary">
            Добавить вклад
        </a>

        <?php if (count($bills) > 0): ?>
            <div class="row" style="margin-top: 20px;">
                <?php foreach ($bills as $bill): ?>
                    <div class="col-md-2" style="text-align: center">
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

                        Тип: <?= $bill->getTypeName() ?><br />
                        Сумма: <?= number_format($bill->sum, 2, '.', ' ') ?> <?= $bill->currency->name ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <p>У вас пока что нет вкладов.</p>
        <?php endif ?>
    </div>
</div>
