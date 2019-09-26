<?php

/**
 * @var $this \yii\web\View
 * @var $content string
 */

use bulldozer\App;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => 'Elwish',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            $menuItems = [
            ];

            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => Yii::t('app', 'Sign up'), 'url' => ['/users/signup']];
                $menuItems[] = ['label' => Yii::t('app', 'Sign in'), 'url' => ['/users/login']];
            } else {
                if (App::$app->user->can('admin_panel')) {
                    $menuItems[] = ['label' => Yii::t('app', 'Admin panel'), 'url' => ['/admin']];
                }

                $menuItems[] = ['label' => 'Кошельки', 'url' => ['/bookkeeping/bills/index']];
                $menuItems[] = ['label' => 'Вклады', 'url' => ['/bookkeeping/bills/contributions']];
                $menuItems[] = ['label' => 'Кредиты', 'url' => ['/bookkeeping/bills/credits']];
                $menuItems[] = ['label' => 'Категории', 'url' => ['/bookkeeping/categories']];
                $menuItems[] = ['label' => 'Статистика', 'url' => ['/bookkeeping/stats']];

                $menuItems[] = [
                    'label' => Yii::t('app', 'Logout ({name})', ['name' => App::$app->user->identity->email]),
                    'url' => ['/users/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Bulldozer CMS <?= date('Y') ?></p>

                <p class="pull-right"><?= App::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
