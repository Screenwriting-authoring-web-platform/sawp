<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\ContactForm $model
 */
$this->title = \Yii::t('app', 'Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

    <div class="alert alert-success">
        <?= \Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.'); ?>
    </div>

    <p>
        <?php if (Yii::$app->mail->useFileTransport): ?>
        Because the application is in development mode, the email is not sent but saved as
        a file under <code><?= Yii::getAlias(Yii::$app->mail->fileTransportPath) ?></code>.
        Please configure the <code>useFileTransport</code> property of the <code>mail</code>
        application component to be false to enable email sending.
        <?php endif; ?>
    </p>

    <?php else: ?>

    <p>
        <?= \Yii::t('app', 'If you have questions, please fill out the following form to contact an admin. Thank you.'); ?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= $form->field($model, 'name')->label(\Yii::t('app', 'Name')) ?>
                <?= $form->field($model, 'email')->label(\Yii::t('app', 'Email')) ?>
                <?= $form->field($model, 'subject')->label(\Yii::t('app', 'Subject')) ?>
                <?= $form->field($model, 'body')->textArea(['rows' => 6])->label(\Yii::t('app', 'Body')) ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ])->label(\Yii::t('app', 'Verification Code')) ?>
                <div class="form-group">
                    <?= Html::submitButton(\Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?php endif; ?>
</div>
