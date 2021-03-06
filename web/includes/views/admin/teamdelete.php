<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'Team delete');

$form = ActiveForm::begin([
    'id' => 'teamdelete-form',
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

    <p><?php echo \Yii::t('app', 'Warning your a about to delete the team "{teamname}"', ["teamname" => Html::encode($team->name)]); ?></p>

    <?= $form->field($model, 'confirm')->checkbox([], false) ?>
    
    <?= Html::activeHiddenInput($model, 'teamid', ["value" => $team->id]) ?> 

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton(\Yii::t('app', 'delete'), ['class' => 'btn btn-primary', 'name' => 'teamdelete-button']) ?>
</div>
<?php ActiveForm::end(); ?>