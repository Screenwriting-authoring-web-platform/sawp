<?php
namespace app\assets;

use yii\web\AssetBundle;

class Select2 extends AssetBundle
{
    public $sourcePath = '@vendor/select2';
    public $css = [
        'select2.css',
    ];
	public $js = [
		'select2.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}