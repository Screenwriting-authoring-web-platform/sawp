<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Setting;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */
 
$this->title = \Yii::t('app', 'Mail config');
?>

<div class="install-mailconfig">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
        'id' => 'adminsettings-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); 

    $encryption = ["tls"=>"tls","ssl"=>"ssl",""=>"(none)",];

    ?>

    <script type="text/javascript">
    function showorhidesmtp(checked) {
        if(checked)
            $('#smtpsettings').show();
        else
            $('#smtpsettings').hide();
    }
    </script>

    <p><?= \Yii::t('app', 'If not checked then your php sendmail will be used.') ?></p>
    <?= $form->field($model, 'mailusesmtp')->checkbox(["onclick"=>"showorhidesmtp(this.checked)"], false)->label(\Yii::t('app', 'use smtp')) ?>
    
    <div id="smtpsettings" style="display: none;">
    <p><?= \Yii::t('app', 'Enter your smtp credentials. They can be from another server or even a freemail provider.') ?></p>
    
    <?= $form->field($model, 'mailhost')->label(\Yii::t('app', 'Mailhost')) ?>
    <?= $form->field($model, 'mailport')->label(\Yii::t('app', 'port (25, 587, 465)')) ?>
    <?= $form->field($model, 'mailusername')->label(\Yii::t('app', 'username')) ?>
    <?= $form->field($model, 'mailpassword')->label(\Yii::t('app', 'password')) ?>
    <?= $form->field($model, 'mailencryption')->dropDownList($encryption)->label(\Yii::t('app', 'encryption')) ?>   

    <script type="text/javascript">
        if(document.getElementById("mailconfigform-mailusesmtp").checked) {
            document.getElementById("smtpsettings").style.display = 'block';
        }
    </script>
    
    </div>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(\Yii::t('app', 'Set'), ['class' => 'btn btn-primary', 'name' => 'mailconfig-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
