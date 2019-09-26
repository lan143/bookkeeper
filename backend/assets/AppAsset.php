<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/templates/admin';

    public $css = [
        'css/theme.css',
        'css/skins/default.css',
        'css/theme-custom.css',
        'css/custom.css',
    ];

    public $js = [
        'vendor/modernizr/modernizr.js',
        'vendor/nanoscroller/nanoscroller.js',
        'js/theme.js',
        'js/theme.custom.js',
        'js/theme.init.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'bulldozer\chosen\ChosenAsset',
        'bulldozer\fontawesome\FontAwesomeAsset',
        'bulldozer\noty\NotyAsset',
    ];
}