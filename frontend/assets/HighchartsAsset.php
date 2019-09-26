<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class HighchartsAsset
 * @package frontend\assets
 */
class HighchartsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/highcharts';

    /**
     * @var array
     */
    public $js = [
        'highcharts.js',
    ];
}