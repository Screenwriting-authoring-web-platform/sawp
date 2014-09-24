<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Setting;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */
 
$this->title = \Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administration'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-addteam">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php echo \Yii::t('app', 'Set new pagesettings'); ?></p>
    
    <?php $form = ActiveForm::begin([
        'id' => 'adminsettings-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); 
    
    
$path = 'includes/messages';
$results = scandir($path);
$languages = [""=>"(".\Yii::t('app', 'none').")"];

foreach ($results as $result) {
    if ($result === '.' or $result === '..') continue;

    if (is_dir($path . '/' . $result)) {
        $languages[$result] = $result;
    }
}

$encryption = ["tls"=>"tls","ssl"=>"ssl",""=>"(none)",];

    ?>

    <?= $form->field($model, 'contactmail')->input('email')->label(\Yii::t('app', 'Contact Email address')) ?> 
    <?= $form->field($model, 'language')->dropDownList($languages)->label(\Yii::t('app', 'language')) ?>
    <?= $form->field($model, 'pageTitle')->label(\Yii::t('app', 'Page Title')) ?>
    <?= $form->field($model, 'pagehomeurl')->label(\Yii::t('app', 'Page Home Url')) ?>
    <?= $form->field($model, 'frontPage')->textarea(["rows" => 8])->label(\Yii::t('app', 'FrontPage content')) ?> 
    <?= $form->field($model, 'defaultCategories')->label(\Yii::t('app', 'Default Tag Categories')) ?> 
    
    <hr />
    
    <?= $form->field($model, 'mailhost')->label(\Yii::t('app', 'Mailhost')) ?>
    <?= $form->field($model, 'mailport')->label(\Yii::t('app', 'port')) ?>
    <?= $form->field($model, 'mailusername')->label(\Yii::t('app', 'username')) ?>
    <?= $form->field($model, 'mailpassword')->label(\Yii::t('app', 'password')) ?>
    <?= $form->field($model, 'mailencryption')->dropDownList($encryption)->label(\Yii::t('app', 'encryption')) ?>   

    <hr />
    
    <?= $form->field($model, 'emailActivation')->checkbox([], false)->label(\Yii::t('app', 'email Activation')) ?> 
    <?= $form->field($model, 'activationMailSender')->label(\Yii::t('app', 'Activation Mail Sender')) ?> 
    <?= $form->field($model, 'activationMailSubject')->label(\Yii::t('app', 'activation Mail Subject')) ?> 
    <?= $form->field($model, 'activationMailBody')->hint("Placeholders: {username}, {activationlink}")->textarea(["rows" => 8])->label(\Yii::t('app', 'Activation Mail Body')) ?> 


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(\Yii::t('app', 'Set'), ['class' => 'btn btn-primary', 'name' => 'adminsettings-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
