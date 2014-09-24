<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = \Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= \Yii::t('app', 'Please fill out the following fields to register'); ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); 
    
    
    
    if($first) echo "<p>".Yii::t('app', 'In this step you will register your (god/root) administrator account.')."</p>\n";
                
    ?>

    <?= $form->field($model, 'username')->label(\Yii::t('app', 'Username')) ?>

    <?= $form->field($model, 'email')->input('email')->label(\Yii::t('app', 'Email')) ?>
    
    <?= $form->field($model, 'emailRepeat')->input('email')->label(\Yii::t('app', 'repeat Email')) ?>
    
    <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
    
    <?= $form->field($model, 'passwordRepeat')->passwordInput()->label(\Yii::t('app', 'repeat Password')) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(\Yii::t('app', 'Register'), ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
