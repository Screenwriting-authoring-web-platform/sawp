<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */
 
$this->title = \Yii::t('app', 'New Screenplay');

$form = ActiveForm::begin([
        'id' => 'addscreenplay-form',
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

    <p><?= \Yii::t('app', 'Please fill out the following fields to create a new screenplay') ?></p>


    <?= $form->field($model, 'name')->label(\Yii::t('app', 'Name')); ?>

    <?= Html::activeHiddenInput($model, 'teamid', ["value" => $team->getId()]) ?> 


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
        <?= Html::submitButton(\Yii::t('app', 'Add Screenplay'), ['class' => 'btn btn-primary', 'name' => 'addscreenplay-button']) ?>
</div>
<?php ActiveForm::end(); ?>


