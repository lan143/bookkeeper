<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\MenuWidget;
use bulldozer\App;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= App::$app->language ?>">
    <head>
        <meta charset="<?= App::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="<?= $bundle->baseUrl ?>/images/favicon.ico" type="image/x-icon"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>


        <style>
            .loader,
            .loader:before,
            .loader:after {
                background: #ffffff;
                -webkit-animation: load1 1s infinite ease-in-out;
                animation: load1 1s infinite ease-in-out;
                width: 1em;
                height: 4em;
            }

            .loader:before,
            .loader:after {
                position: absolute;
                top: 0;
                content: '';
            }

            .loader:before {
                left: -1.5em;
                -webkit-animation-delay: -0.32s;
                animation-delay: -0.32s;
            }

            .loader {
                color: #ffffff;
                text-indent: -9999em;
                margin: 88px auto;
                position: relative;
                font-size: 11px;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation-delay: -0.16s;
                animation-delay: -0.16s;
                top: 30%
            }

            .loader:after {
                left: 1.5em;
            }

            @-webkit-keyframes load1 {
                0%,
                80%,
                100% {
                    box-shadow: 0 0;
                    height: 4em;
                }
                40% {
                    box-shadow: 0 -2em;
                    height: 5em;
                }
            }

            @keyframes load1 {
                0%,
                80%,
                100% {
                    box-shadow: 0 0;
                    height: 4em;
                }
                40% {
                    box-shadow: 0 -2em;
                    height: 5em;
                }
            }

            div#preloader {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 999;
                width: 100%;
                height: 100%;
                overflow: visible;
                background-color: #000;
            }
        </style>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div id="preloader">
        <div class="loader"><?= Yii::t('app', 'Loading...') ?></div>
    </div>

    <section class="body">
        <!-- start: header -->
        <header class="header">
            <div class="logo-container">
                <a href="<?= Url::home() ?>" class="logo">
                    <img src="<?= $bundle->baseUrl ?>/images/logo.png" height="35" alt="<?= Yii::t('app', 'Bulldozer CMS Admin panel') ?>"/>
                </a>
                <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
                     data-fire-event="sidebar-left-opened">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <!-- start: search & user box -->
            <div class="header-right">
                <?php if (!App::$app->user->isGuest): ?>
                    <span class="separator"></span>

                    <div id="userbox" class="userbox">
                        <a href="#" data-toggle="dropdown">
                            <figure class="profile-picture">
                                <img src="<?= $bundle->baseUrl ?>/images/!logged-user.jpg"
                                     alt="<?= App::$app->user->identity->email ?>" class="img-circle"
                                     data-lock-picture="<?= $bundle->baseUrl ?>/images/!logged-user.jpg"/>
                            </figure>
                            <div class="profile-info">
                                <span class="name"><?= App::$app->user->identity->email ?></span>
                            </div>

                            <i class="fa custom-caret"></i>
                        </a>

                        <div class="dropdown-menu">
                            <ul class="list-unstyled">
                                <li class="divider"></li>

                                <li>
                                    <a role="menuitem" tabindex="-1" href="<?= Url::to(['/users/logout']) ?>"
                                       data-method="post"><i class="fa fa-power-off"></i> Выйти</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div id="userbox" class="userbox">
                        <a href="<?= Url::to(['/users/login']) ?>"><?= Yii::t('app', 'Sign in') ?></a>
                    </div>
                <?php endif ?>
            </div>
            <!-- end: search & user box -->
        </header>
        <!-- end: header -->

        <div class="inner-wrapper">
            <!-- start: sidebar -->
            <?php if (!App::$app->user->isGuest): ?>
                <?= MenuWidget::widget(['menuItems' => App::$app->menu->menuItems]) ?>
            <?php endif ?>
            <!-- end: sidebar -->

            <section role="main" class="content-body">
                <header class="page-header">
                    <h2><?= $this->title ?></h2>

                    <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                            <li>
                                <a href="<?= Url::home() ?>">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <?php if (isset($this->params['breadcrumbs']) && count($this->params['breadcrumbs']) > 0): ?>
                                <?php foreach ($this->params['breadcrumbs'] as $breadcrumb): ?>
                                    <?php if (is_array($breadcrumb)): ?>
                                        <?php if (isset($breadcrumb['url'])): ?>
                                            <?php if (is_array($breadcrumb['url'])): ?>
                                                <li><a href="<?= Url::to($breadcrumb['url']) ?>"><span><?= $breadcrumb['label'] ?></span></a></li>
                                            <?php else: ?>
                                                <li><a href="<?= $breadcrumb['url'] ?>"><span><?= $breadcrumb['label'] ?></span></a></li>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <li><span><?= $breadcrumb['label'] ?></span></li>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <li><span><?= $breadcrumb ?></span></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ol>

                        <a class="sidebar-right-toggle"></a>
                    </div>
                </header>

                <?= $content ?>
            </section>
        </div>
    </section>

    <script>
        <?php if (App::$app->session->hasFlash('success')): ?>
        var alert_success = '<?= App::$app->session->getFlash('success') ?>';
        <?php else: ?>
        var alert_success = null;
        <?php endif ?>

        <?php if (App::$app->session->hasFlash('error')): ?>
        var alert_danger = '<?= App::$app->session->getFlash('error') ?>';
        <?php else: ?>
        var alert_danger = null;
        <?php endif ?>
    </script>
    <?php $this->head() ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>