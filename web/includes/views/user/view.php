<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\RegisterForm $model
 */
$this->title = Html::encode($user->username);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">

    <?php
        if($user->id===\Yii::$app->user->identity->id) {
        ?>
        
        <a class="btn btn-sm pull-right btn-default showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['user/editprofile']); ?>">
            <?= \Yii::t('app', 'Edit Profile'); ?>
        </a>
        
        <?php
        }
    
    ?>
    
    <h1><?= $user->get_gravatar($s = 80, $d = 'identicon', $r = 'g', $img = true ) ?> <?= Html::encode($this->title) ?></h1>

</div>
