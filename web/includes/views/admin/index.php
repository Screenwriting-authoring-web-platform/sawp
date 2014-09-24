<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = Yii::t('app', 'Administration');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-adminindex">
    <h1><?= Html::encode($this->title) ?></h1>

    <ul class="list-group">
      <li class="list-group-item"><a href="<?php echo \Yii::$app->urlManager->createUrl(['admin/userlist']); ?>" ><?= Yii::t('app', 'User list'); ?></a></li>
      <li class="list-group-item"><a href="<?php echo \Yii::$app->urlManager->createUrl(['admin/teamlist']); ?>" ><?= Yii::t('app', 'Team list'); ?></a></li>
      <li class="list-group-item"><a href="<?php echo \Yii::$app->urlManager->createUrl(['admin/settings']); ?>" ><?= Yii::t('app', 'Settings'); ?></a></li>
    </ul>

</div>
