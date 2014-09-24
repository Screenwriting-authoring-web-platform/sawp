<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */

$this->title = \Yii::t('app', 'prerequisite test');

?>
<div class="site-myteams">
    <h1><?= Html::encode($this->title) ?></h1>

   <div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><?= Yii::t('app', 'PHP Moduls')?></div>

  <!-- List group -->
  <ul class="list-group">

    
    <?php 
    
    
    foreach($model->checkModules() as $module => $status) {
        if($status) {
            $style = 'class="list-group-item list-group-item-success"';
            $txt = Yii::t('app', 'Active');
        } else {
            $style = 'class="list-group-item list-group-item-danger"';
            $txt = Yii::t('app', 'Missing');
        }
        ?>
        <li <?php echo $style; ?>>
        <?= Html::encode($module); ?> - <?= Html::encode($txt)?>
         </li>

    <?php } ?>

  </ul>
</div>
   
  <div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><?= Yii::t('app', 'Read & Write Permissions')?></div>

  <!-- List group -->
  <ul class="list-group">

    
    <?php foreach($model->checkPermissions() as $path => $status) {
        if($status) {
            $style = 'class="list-group-item list-group-item-success"';
            $txt = Yii::t('app', 'writable');
        } else {
            $style = 'class="list-group-item list-group-item-danger"';
            $txt = Yii::t('app', 'should be writable');
        }
        ?>
        <li <?php echo $style; ?>>
        <?= Html::encode($path); ?> - <?= Html::encode($txt)?>
         </li>

    <?php } ?>

  </ul>
</div>
   
   <?php if(!$model->getErrorOccured()) { ?>
   <a class="btn btn-primary" href="<?php echo \Yii::$app->urlManager->createUrl(['install/mailconfig']); ?>"><?= Yii::t('app', 'Next Step')?></a>
   <?php } else { ?>
   <a class="btn btn-primary" href="<?php echo \Yii::$app->urlManager->createUrl(['install/testmodules']); ?>" onclick="location.reload();"><?= Yii::t('app', 'Test again')?></a>
<?php } ?>
   
</div>
