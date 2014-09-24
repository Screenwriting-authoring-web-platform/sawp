<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'database configuration');
?>
<div class="install-dbconfig">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'dbconfig-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <?= $form->field($model, 'server')->textInput(array('value' => 'localhost'))->label(\Yii::t('app', 'Server'))  ?>
    
    <?= $form->field($model, 'username')->label(\Yii::t('app', 'Username')) ?>

    <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
    
    <?= $form->field($model, 'database')->label(\Yii::t('app', 'Database')) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'dbconfig-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
