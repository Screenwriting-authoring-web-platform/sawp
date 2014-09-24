<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */
 
$this->title = \Yii::t('app', 'New Team');
$form = ActiveForm::begin([
    'id' => 'adminaddteam-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]); 

    $items = array();
    foreach($userlist as $u) $items[$u->getId()]=$u->username; //dropDownList() does Html::encode()
    
?>
   
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">


    <?= $form->field($model, 'ownerid')->dropDownList($items); ?>
    <?= $form->field($model, 'ispublic')->checkbox([], false) ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?></button>
    <?= Html::submitButton('add team', ['class' => 'btn btn-primary', 'name' => 'adminaddteam-button']) ?>
</div>
<?php ActiveForm::end(); ?>