<?php

namespace app\assets;

use yii\web\AssetBundle;

class JqueryScrollToAsset extends AssetBundle
{
    public $sourcePath = '@vendor/jquery-scrollto';
    public $css = [];
	public $js = [
		'jquery.scrollTo.min.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}