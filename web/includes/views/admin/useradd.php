<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = \Yii::t('app', 'Add User');



$form = ActiveForm::begin([
    'id' => 'adminadduser-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]); ?>
   
    
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">

    <p><?php echo \Yii::t('app', 'Please fill out the following fields to register a new user'); ?>:</p>

    <?= $form->field($model, 'username')->label(\Yii::t('app', 'Name')) ?>

    <?= $form->field($model, 'email')->input('email')->label(\Yii::t('app', 'Email')) ?>
    
    <?= $form->field($model, 'emailRepeat')->input('email')->label(\Yii::t('app', 'repeat Email')) ?>
    
    <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
    
    <?= $form->field($model, 'passwordRepeat')->passwordInput()->label(\Yii::t('app', 'repeat Password')) ?>

    <?= $form->field($model, 'isadmin')->checkbox([], false)->label(\Yii::t('app', 'is Admin')) ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton(\Yii::t('app', 'Add User'), ['class' => 'btn btn-primary', 'name' => 'adminadduser-button']) ?>
</div>
<?php ActiveForm::end(); ?>