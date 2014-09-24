<?php

namespace app\assets;

use yii\web\AssetBundle;

class I18NAsset extends AssetBundle
{
    public $sourcePath = '@webroot/js';
    public $css = [];
	public $js = [
		'jquery.i18n-dict.js.php','jquery.i18n-wrapper.js'
	];
    public $depends = [
        'app\assets\JQueryI18NAsset',
    ];
}