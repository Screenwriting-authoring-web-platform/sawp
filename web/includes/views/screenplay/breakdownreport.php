<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile('js/breakdownreport.js',["app\assets\JqueryUIAsset"]);

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'Generate Breakdownreport');

$form = ActiveForm::begin([
        'id' => 'breakdownreport-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); 
    
    $ra = array();
    $ra[0] = \Yii::t('app', 'choose type');
    $ra[1] = \Yii::t('app', 'Breakdown by Scenes');
    $ra[2] = \Yii::t('app', 'Breakdown by Tags');
    $ra[4] = \Yii::t('app', 'Statistics');
?>


<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::activeHiddenInput($model, 'screenplayid', ["value" => $screenplay->getId()]) ?> 

    <?= $form->field($model, 'type')->dropDownList($ra)->label(\Yii::t('app', 'Type')); ?>
    
    <?= $form->field($model, 'graphic')->checkbox([], false)->label(Yii::t('app', 'Table view')); ?>
    
    <a class="btn btn-sm btn-default pull-right selectallscenes" style="margin-left: 5px;"><?= \Yii::t('app', 'select all') ?></a>
    <a class="btn btn-sm btn-default pull-right deselectallscenes"><?= \Yii::t('app', 'deselect all') ?></a>
    <?= $form->field($model, 'scenes')->checkboxList($screenplay->getScenes())->label(\Yii::t('app', 'Scenes')) ?>
    
    <span class="field-breakdownform-charnotice"><?= \Yii::t('app', 'choose categories with tags you want to count') ?>:</span><br /><br />
    
    <a class="btn btn-sm btn-default pull-right selectallcategories" style="margin-left: 5px;"><?= \Yii::t('app', 'select all') ?></a>
    <a class="btn btn-sm btn-default pull-right deselectallcategories"><?= \Yii::t('app', 'deselect all') ?></a>
    <div class="form-group field-breakdownform-categories">
        <label class="col-lg-2 control-label" for="breakdownform-categories"><?= \Yii::t('app', 'Categories') ?></label>
        <div class="col-lg-3"><input name="BreakdownForm[categories]" value="" type="hidden">
            <?php
                foreach($screenplay->getCategories() as $cat) {
                    $left = 20*$cat["deep"];
                    echo '<div style="margin-left: '.$left.'px;" class="checkbox"><label><input name="BreakdownForm[categories][]" value="'.$cat["key"].'" type="checkbox">'.Html::encode($cat["title"]).'</label></div>'."\n";
                }
            ?>
            <div class="col-lg-8">
                <div class="help-block">
                </div>
            </div>
        </div> 
    </div>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton(\Yii::t('app', 'generate'), ['class' => 'btn btn-primary', 'name' => 'breakdownreport-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

