<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */

$this->title = \Yii::t('app', 'SAWP Installer');

?>
<div class="site-myteams">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('app', 'Press Start to Install SWAP on your webspace') ?></p>

   <a class="btn btn-primary" href="<?php echo \Yii::$app->urlManager->createUrl(['install/testmodules']); ?>"><?= Yii::t('app', 'Start') ?></a>   
   
</div>
