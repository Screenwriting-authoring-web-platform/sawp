<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */

$this->title = \Yii::t('app', 'Edit Team');

$form = ActiveForm::begin([
    'id' => 'addteam-form',
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

    <p><?php echo \Yii::t('app', 'Please fill out the following fields to edit a team'); ?>:</p>
    

    <?= Html::activeHiddenInput($model, 'teamid', ["value" => $team->getId()]) ?> 
    <?= $form->field($model, 'ispublic')->checkbox([], false) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?> 

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton('Edit Team', ['class' => 'btn btn-primary', 'name' => 'admineditteam-button']) ?>
</div>
<?php ActiveForm::end(); ?>