<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class QRAsset
 * @package frontend\assets
 */
class QRAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@frontend/assets/qrcode';

    /**
     * @var array
     */
    public $js = [
        'instascan.min.js',
    ];
}