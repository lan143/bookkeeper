<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\ar\Bill[] $bills
 */

use common\enums\BillTypes;
use yii\helpers\Url;

$this->title = 'Кошельки';
$this->params['breadcrumbs'][] = 'Кошельки';

?>
<style>
    .card {
        border-radius: 15px;
        width: 100%;
        background: #000;
        display: -webkit-box;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        flex-direction: column;
        -webkit-box-pack: justify;
        justify-content: space-between;
        -webkit-box-align: start;
        align-items: flex-start;
        box-sizing: border-box;
        box-shadow: 2px 2px 10px #999;
        transition: .6s background-color, .6s color;
        overflow: hidden;
    }

    .card__bank-name {
        text-align: left;
        height: 4rem;
        line-height: 4rem;
        margin: 0;
        text-transform: uppercase;
        padding: 0 2rem;
        width: 100%;
        box-sizing: border-box;
        color: #fff;
    }

    .card__card-name {
        text-align: left;
        margin: 0;
        padding: 0 2rem;
        width: 100%;
        box-sizing: border-box;
        color: #fff;
    }

    i {
        color: #fff;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <a href="<?= Url::to(['bills/create', 'type' => BillTypes::CASH]) ?>" class="btn btn-primary">
            Добавить наличные
        </a>

        <a href="<?= Url::to(['bills/create', 'type' => BillTypes::CARD]) ?>" class="btn btn-primary">
            Добавить дебетовую карту
        </a>

        <a href="<?= Url::to(['bills/create', 'type' => BillTypes::CREDIT_CARD]) ?>" class="btn btn-primary">
            Добавить кредитную карту
        </a>
        
        <?php if (count($bills) > 0): ?>
            <div class="row" style="margin-top: 20px;">
                <?php foreach ($bills as $bill): ?>
                    <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0 col-md-offset-0 col-md-3 col-lg-offset-0 col-lg-3" style="text-align: center; padding: 20px;">
                        <a href="<?= Url::to(['bills/view', 'id' => $bill->id]) ?>">
                            <?php
                            $cardInfo = $bill->getCardInfo();
                            ?>
                            <div class="card" <?php if ($cardInfo && !$cardInfo['is_unknown']): ?>style="background-color: <?= $cardInfo['color'] ?>"<?php endif ?>>
                                <?php if ($cardInfo): ?>
                                    <p class="card__bank-name">
                                        <?= $cardInfo['name'] ?>
                                    </p>
                                <?php endif ?>

                                <div class="text-center" style="width: 100%; font-size: 4em;">
                                    <?php if ($cardInfo): ?>
                                        <?php if ($cardInfo['type'] == 'mastercard'): ?>
                                            <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                                        <?php elseif ($cardInfo['type'] == 'visa'): ?>
                                            <i class="fa fa-cc-visa" aria-hidden="true"></i>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <?php if ($bill->type == BillTypes::CASH): ?>
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        <?php elseif ($bill->type == BillTypes::CARD): ?>
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        <?php elseif ($bill->type == BillTypes::CREDIT_CARD): ?>
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>

                                <p class="card__card-name">
                                    <?= $bill->name ?><br />
                                    <?= $bill->getTypeName() ?><br />
                                    <?= number_format($bill->sum, 2, '.', ' ') ?> <?= $bill->currency->name ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
        <?php else: ?>
            <p>У вас пока что нет счетов.</p>
        <?php endif ?>
    </div>
</div>
