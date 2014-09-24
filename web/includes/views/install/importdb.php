<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'database import');
?>
<div class="install-dbconfig">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if(!$model->importDB($connection)) {
        ?> <div class="alert alert-danger" role="alert"><?= \Yii::t('app', 'Database Import Failed') ?></div> <?php
    } else {
        if(!$model->setInstalled()) {
            ?> <div class="alert alert-danger" role="alert"><?= \Yii::t('app', 'Could not write to "{path}"', ["path"=>"config/installed.php"]) ?></div> <?php
        } else {
            ?> <div class="alert alert-success" role="alert"><?= \Yii::t('app', 'Database Import Completed') ?></div>
            <a class="btn btn-primary" href="<?php echo \Yii::$app->urlManager->createUrl(['site/register']); ?>"><?= \Yii::t('app', 'Next Step') ?></a> <?php
        }
    
    }
    ?>
</div>
