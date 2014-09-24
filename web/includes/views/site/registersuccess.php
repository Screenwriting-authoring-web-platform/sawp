<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$this->title = \Yii::t('app', 'Successfully registered');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= \Yii::t('app', 'Your account was successfully created, to complete your registration visit the link sent to you via mail.') ?>
    </p>
</div>
