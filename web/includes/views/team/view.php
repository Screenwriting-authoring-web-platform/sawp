<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\Nav;
use app\models\User;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\Team $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Teams'), 'url' => ['team/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-myteams">
    <h1><?= Html::encode($this->title); ?></h1>
    <p><?= Html::encode($model->description); ?></p>

    <h3><?php echo \Yii::t('app', 'Collaborators'); ?></h3>
    <h4><?php echo \Yii::t('app', 'Directors'); ?></h4>

    <?php foreach ($model->getDirectors() as $user) { ?> 
        <div class="row top-buffer">
            <div class="col-md-8"><?= Html::encode($user->username); ?><?php if($user->isActive()) { ?> <span class="glyphicon glyphicon-record" style="color:green;"></span><?php  } ?></div>
            <?php if(\Yii::$app->user->can('setUsersTeamRole',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/setuserright', 'teamid' => $model->getId(), 'userid' => $user->getId()]); ?>">
                    <?= \Yii::t('app', 'change role') ?>
                </a>
            </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if(count($model->getArtists()) !== 0) { ?>
    <h4><?= \Yii::t('app', 'Artists'); ?></h4>

    <?php } foreach ($model->getArtists() as $user) { ?> 
        <div class="row top-buffer">
            <div class="col-md-8"><?= Html::encode($user->username); ?><?php if($user->isActive()) { ?> <span class="glyphicon glyphicon-record" style="color:green;"></span><?php  } ?></div>
            <?php if(\Yii::$app->user->can('kickUserfromTeam',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
            <a class="btn btn-sm btn-danger showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/kickuser', 'teamid' => $model->getId(), 'userid' => $user->getId()]); ?>">
                <span class="glyphicon glyphicon-user"></span> <?= \Yii::t('app', 'kick artist'); ?>
            </a>
            </div>
            <?php } if(\Yii::$app->user->can('setUsersTeamRole',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
            <a class="btn btn-sm btn-primary showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/setuserright', 'teamid' => $model->getId(), 'userid' => $user->getId()]); ?>">
                <?= \Yii::t('app', 'change role'); ?>
            </a>
            </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if(count($model->getObservers()) !== 0) { ?>
    <h4><?php echo \Yii::t('app', 'Observers'); ?></h4>

    <?php } foreach ($model->getObservers() as $user) { ?> 
        <div class="row top-buffer">
            <div class="col-md-8"><?= Html::encode($user->username); ?><?php if($user->isActive()) { ?> <span class="glyphicon glyphicon-record" style="color:green;"></span><?php  } ?></div>
            <?php if(\Yii::$app->user->can('kickUserfromTeam',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
                <a class="btn btn-sm btn-danger showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/kickuser', 'teamid' => $model->getId(), 'userid' => $user->getId()]); ?>">
                    <span class="glyphicon glyphicon-user"></span> <?php echo \Yii::t('app', 'kick observer'); ?>
                </a>
            </div>
            <?php } if(\Yii::$app->user->can('setUsersTeamRole',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
            <a class="btn btn-sm btn-primary showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/setuserright', 'teamid' => $model->getId(), 'userid' => $user->getId()]); ?>">
                <?php echo \Yii::t('app', 'change role'); ?>
            </a>
            </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-8">
            <h3><?php echo \Yii::t('app', 'Screenplay'); ?></h3>
        </div>
    </div>
    
    <div class="row">   
        <div class="col-md-8">
        </div>
        <?php if(\Yii::$app->user->can('addScreenplay',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
        <div class="col-md-2">
            <a class="btn btn-sm btn-success showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['screenplay/add', 'id' => $model->getId()]); ?>">
                <span class="glyphicon glyphicon-plus"></span> <?php echo \Yii::t('app', 'Add new screenplay'); ?>
            </a>
        </div>
        <?php } if(\Yii::$app->user->can('importFile',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
        <div class="col-md-2">
            <a class="btn btn-sm btn-success" href="<?php echo \Yii::$app->urlManager->createUrl(['team/import', 'id' => $model->getId()]); ?>">
                <span class="glyphicon glyphicon-plus"></span> <?php echo \Yii::t('app', 'Import File'); ?>
            </a>
        </div>
        <?php } ?>
    </div>
     
    <?php if(count($model->getScreenplays()) === 0) { ?>
        <h4 class="list-group-item-heading"><?php echo \Yii::t('app', 'You don´t have any screenplays yet. Create a new one!'); ?></h4>
    <?php } else foreach ($model->getScreenplays() as $screenplay) { ?> 
    <div class="list-group-item"> 
        <div class="row top-buffer">
            <div class="col-md-6"><?= Html::encode($screenplay->name) ?> <?php if($screenplay->isLocked() && $screenplay->isLocked() !== \Yii::$app->user->identity->id) { ?><br /> <div class="well well-sm"><?php echo \Yii::t('app', 'Screenplay is currently edited by {username}', ['username' => User::findIdentity($screenplay->isLocked())->username]); ?></div><?php } ?></div>
            <?php if(\Yii::$app->user->can('saveScreenplayContent',["team" => $model, "user" => \Yii::$app->user->identity]) && !($screenplay->isLocked() && $screenplay->isLocked() !== \Yii::$app->user->identity->id)) { ?>
            <div class="col-md-1">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/screenplay/showeditor', 'id' => $screenplay->getId()]); ?>" class="btn btn-primary btn-sm"><i class="icon-white icon-edit"></i><?php echo \Yii::t('app', 'edit'); ?></a>
            </div>
            <?php } else { ?>
            <div class="col-md-1">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/screenplay/showeditor', 'id' => $screenplay->getId()]); ?>" class="btn btn-primary btn-sm"><i class="icon-white icon-edit"></i><?php echo \Yii::t('app', 'view'); ?></a>
            </div>
            <?php } if(\Yii::$app->user->can('exportScreenplay',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-1">
                <a class="btn btn-primary btn-sm showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['screenplay/export', 'teamid' => $model->id, 'screenplayid' => $screenplay->getId() ]); ?>" ><?= Yii::t('app', 'export'); ?></a>
            </div>
            <?php } if(\Yii::$app->user->can('generateBreakdown',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-2">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/screenplay/breakdownreport', 'id' => $screenplay->getId()]); ?>" class="btn btn-primary btn-sm"><i class="icon-white icon-edit"></i><?php echo \Yii::t('app', 'Breakdownreport'); ?></a>
            </div>
            <?php } if(\Yii::$app->user->can('showHistory',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
            <div class="col-md-1">
                <a href="<?php echo Yii::$app->urlManager->createUrl(['/screenplay/history', 'id' => $screenplay->getId()]); ?>" class="btn btn-primary btn-sm"><i class="icon-white icon-edit"></i><?php echo \Yii::t('app', 'History'); ?></a>
            </div>
            <?php } if(\Yii::$app->user->can('deleteScreenplay',["team" => $model, "user" => \Yii::$app->user->identity])) { ?>
                <div class="col-md-1">
                    <a href="<?php echo Yii::$app->urlManager->createUrl(['/screenplay/delete', 'id' => $screenplay->getId()]); ?>" class="btn btn-danger btn-sm showinmodal"><i class="icon-white icon-edit"></i><?php echo \Yii::t('app', 'Delete'); ?></a>
                </div>

            <?php } ?>
        </div>
    </div>     
    <?php } ?>
       

</div>

</div>
