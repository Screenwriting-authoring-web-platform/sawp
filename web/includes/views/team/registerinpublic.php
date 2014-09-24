<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'Register in public team');

$userteams=\Yii::$app->user->identity->getTeams();
$userteamsid = array();
foreach ($userteams as $up){
    $userteamsid[] = $up->id;
}

$teamlist = array();
foreach ($teams as $team){
    if(!in_Array($team->getId(),$userteamsid))
    $teamlist[$team->getId()] = $team->name;
}


$form = ActiveForm::begin([
        'id' => 'registerinpulic-form',
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

    <p><?= \Yii::t('app', 'Check the teams you want to collaborate') ?></p>
    

    <?php
    $ra = array();
    $ra[1] = \Yii::t('app', 'Artist');
    $ra[2] = \Yii::t('app', 'Observer');
    ?>

    <?= $form->field($model, 'teams')->checkboxList($teamlist)->label(\Yii::t('app', 'Teams')) ?>
    
    <?= $form->field($model, 'right')->dropDownList($ra)->label(\Yii::t('app', 'Role')) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close') ?> </button>
    <?= Html::submitButton(\Yii::t('app', 'register in teams'), ['class' => 'btn btn-primary', 'name' => 'registerinpulic-button']) ?>
</div>

<?php ActiveForm::end(); ?>
