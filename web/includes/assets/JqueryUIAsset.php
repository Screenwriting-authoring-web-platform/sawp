<?php

namespace app\assets;

use yii\web\AssetBundle;

class JqueryUIAsset extends AssetBundle
{
    public $sourcePath = '@vendor/jquery-ui';
    public $css = [];//['css/ui-lightness/jquery-ui-1.10.4.css'];
	public $js = [
		'js/jquery-ui-1.10.4.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}