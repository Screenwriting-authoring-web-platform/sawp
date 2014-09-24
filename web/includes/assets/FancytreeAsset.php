<?php

namespace app\assets;

use yii\web\AssetBundle;

class FancytreeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/fancytree';
    public $css = ['dist/skin-sawp/ui.fancytree.css'];
    public $js = [
	'dist/jquery.fancytree.js',
        'dist/src/jquery.fancytree.edit.js',
        'dist/src/jquery.fancytree.dnd.js',
        'dist/src/jquery.fancytree.childcounter.js',
        'dist/src/jquery.fancytree.glyph.js',
	];
    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\JqueryUIAsset',
         'yii\bootstrap\BootstrapAsset'
    ];
}