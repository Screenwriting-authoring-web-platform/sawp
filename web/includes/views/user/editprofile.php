<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = \Yii::t('app', 'Edit Profile');

$form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-3 control-label'],
        ],
    ]); 
    
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">

    <p><?= \Yii::t('app', 'Please fill out the following fields to register'); ?></p>

    <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'current Password')) ?>
    
    <?= $form->field($model, 'email')->input('email',["placeholder"=>\Yii::$app->user->identity->mailAddress])->label(\Yii::t('app', 'Email')) ?>
    
    <?= $form->field($model, 'emailRepeat')->input('email')->label(\Yii::t('app', 'Repeat Email')) ?>
    
    <?= $form->field($model, 'gravatarMailAddress')->input('email',["placeholder"=>\Yii::$app->user->identity->gravatarMailAddress])->label(\Yii::t('app', 'Gravatar Email')) ?>
    
    <?= $form->field($model, 'newPassword')->passwordInput()->label(\Yii::t('app', 'new Password')) ?>
    
    <?= $form->field($model, 'newPasswordRepeat')->passwordInput()->label(\Yii::t('app', 'repeat new password')) ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton(\Yii::t('app', 'Edit Profile'), ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
</div>

<?php ActiveForm::end(); ?>
