<?php

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die("PHP version is to old. Required 5.4 or newer.");
}

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/includes/vendor/autoload.php');
require(__DIR__ . '/includes/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/includes/config/web.php');

(new yii\web\Application($config))->run();
