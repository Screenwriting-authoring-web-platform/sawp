<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = \Yii::t('app', 'Edit User');

$form = ActiveForm::begin([
    'id' => 'adminedituser-form',
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

    <p><?php echo \Yii::t('app', 'Please fill out the following fields to edit the user "{username}"',["username"=>Html::encode($user->username)]); ?></p>

 
    <?= Html::activeHiddenInput($model, 'userid', ["value" => $user->getId()]) ?> 

    <?= $form->field($model, 'email')->input('email')->label(\Yii::t('app', 'Email')) ?>
    
    <?= $form->field($model, 'emailRepeat')->input('email')->label(\Yii::t('app', 'repeat Email')) ?>
    
    <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('app', 'Password')) ?>
    
    <?= $form->field($model, 'passwordRepeat')->passwordInput()->label(\Yii::t('app', 'repeat Password')) ?>

    <?= $form->field($model, 'isadmin')->checkbox([], false)->label(\Yii::t('app', 'is admin')) ?>
    
    <?= $form->field($model, 'status')->dropDownList(User::getStatusArray())->label(\Yii::t('app', 'status'));
        
        
    ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton(\Yii::t('app', 'Edit User'), ['class' => 'btn btn-primary', 'name' => 'adminedituser-button']) ?>
</div>
<?php ActiveForm::end(); ?>
