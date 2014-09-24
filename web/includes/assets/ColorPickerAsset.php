<?php

namespace app\assets;

use yii\web\AssetBundle;

class ColorPickerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/simplecolorpicker';
    public $css = ['jquery.simplecolorpicker.css','jquery.simplecolorpicker-glyphicons.css'];
	public $js = [
		'jquery.simplecolorpicker.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}