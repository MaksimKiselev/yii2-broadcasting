<?php

namespace mkiselev\broadcasting\assets;

use yii\web\AssetBundle;

class EchoAsset extends AssetBundle
{
    public $sourcePath = '@vendor/mkiselev/yii2-broadcasting/assets/echo';

    public $js = [
        (YII_DEBUG) ? 'js/echo.js' : 'js/echo.min.js',
    ];
}
