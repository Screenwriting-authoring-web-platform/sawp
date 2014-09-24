<?php

namespace app\assets;

use yii\web\AssetBundle;

class JQueryI18NAsset extends AssetBundle
{
    public $sourcePath = '@vendor/jquery-i18n';
    public $css = [];
	public $js = [
		'jquery.i18n.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}