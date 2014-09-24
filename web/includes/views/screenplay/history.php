<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile('js/history.js',['yii\web\JqueryAsset','app\assets\I18NAsset']);
$this->registerCssFile('css/history.css');

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'Screenplay history');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Teams'), 'url' => ['team/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($screenplay->getTeam()->name), 'url' => ['team/view','id'=>$screenplay->getTeam()->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-userdelete">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'screenplayhistory-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); 
    
    $revisionHeaders = $screenplay->getRevisionHeaders();
    
    ?>

    <?= Html::activeHiddenInput($model, 'revision') ?>
    <?= Html::activeHiddenInput($model, 'screenplayId', ["value" => $screenplay->getId()]) ?>

    

    <div class="form-group">
            
        <div class="col-lg-12">
            <span id="timestamp"><?php echo $revisionHeaders[count($revisionHeaders)-1]["creation_time"]; ?></span><?= Html::submitButton(\Yii::t('app', 'Revert'), ['class' => 'btn pull-right btn-primary', 'name' => 'screenplayhistory-button']) ?>
        </div>
    
        <input type="range" id="timeslider" name="points" min="0" max="<?php echo count($revisionHeaders)-1; ?>" value="<?php echo count($revisionHeaders)-1; ?>" data-revisionheaders='<?php echo json_encode($revisionHeaders); ?>'>
        
        <div style="margin: 0 auto; margin-top: 20px; width: 600px;min-height: 774px;padding: 15px; border: 1px solid grey;" id="content"><?= $screenplay->getLastRevision() ?></div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
