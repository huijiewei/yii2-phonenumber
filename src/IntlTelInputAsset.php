<?php
/**
 * Created by PhpStorm.
 * User: Huijiewei
 * Date: 2014/11/27
 * Time: 15:49
 */

namespace huijiewei\phonenumber;

use yii\web\AssetBundle;

class IntlTelInputAsset extends AssetBundle
{
    public $sourcePath = '@npm/intl-tel-input/build';

    public $css = [
        'css/intlTelInput.min.css'
    ];

    public $js = [
        'js/utils.js',
        'js/intlTelInput.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
