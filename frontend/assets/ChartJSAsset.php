<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class ChartJSAsset
 * @package frontend\assets
 */
class ChartJSAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/chart.js';

    /**
     * @var array
     */
    public $js = [
        'dist/Chart.bundle.min.js',
    ];
}