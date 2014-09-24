<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Team;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = \Yii::t('app', 'Edit Userrole');

$form = ActiveForm::begin([
        'id' => 'userright-form',
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

    <p><?= \Yii::t('app', 'Please fill out the following fields to change a userrole')." ".Html::encode($user->username) ?></p>


    
    <?= Html::activeHiddenInput($model, 'userid') ?> 

    <?= Html::activeHiddenInput($model, 'teamid') ?> 

    <?= $form->field($model, 'right')->dropDownList(Team::getRoleArray())->label(\Yii::t('app', 'Role'));
        
        
    ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
        <?= Html::submitButton(\Yii::t('app', 'Edit Userrole'), ['class' => 'btn btn-primary', 'name' => 'userright-button']) ?>
</div>
<?php ActiveForm::end(); ?>
      