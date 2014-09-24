<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'Import File');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Teams'), 'url' => ['team/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($team->name), 'url' => ['team/view', 'id' => $team->getId()]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-import">
    <h1><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'import-form',
        'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'], 
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'filecontent')->fileInput()->label(\Yii::t('app', 'File')) ?>
    <?= Html::activeHiddenInput($model, 'teamId', ["value" => $team->getId()]) ?> 

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(\Yii::t('app', 'Import'), ['class' => 'btn btn-primary', 'name' => 'import-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
