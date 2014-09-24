<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\LoginForm $model
 */
$this->title = \Yii::t('app', 'My Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-myteams">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (\Yii::$app->user->can('createTeam')) { ?>
        <a class="btn btn-sm btn-success showinmodal" href="<?= \Yii::$app->urlManager->createUrl(['team/add']); ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= \Yii::t('app', 'Create new team') ?>
        </a>
        <?php  } if (\Yii::$app->user->can('registerInPublicTeam')) { ?>
        <a class="btn btn-sm btn-success showinmodal" href="<?= \Yii::$app->urlManager->createUrl(['team/registerinpublic']); ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= \Yii::t('app', 'Register in public team') ?>
        </a>
        <?php } ?>
    </p>

    <div class="list-group">
    <?php if(count($model->getTeams()) === 0) { ?>
        <h4 class="list-group-item-heading"><?php echo \Yii::t('app', 'You don´t have any teams yet. Create a new one!'); ?></h4>
    <?php } else foreach($model->getTeams() as $team) { ?>
        <div class="list-group-item">

            <?php if (\Yii::$app->user->can('leaveTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) {  ?>
            <a style="margin-left: 5px;" class="btn btn-sm btn-danger pull-right showinmodal" href="<?= \Yii::$app->urlManager->createUrl(['team/leave', 'id' => $team->id]); ?>">
                <span class="glyphicon glyphicon-minus"></span> <?= \Yii::t('app', 'Leave Team'); ?>
            </a>
            <?php } if (\Yii::$app->user->can('deleteTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) { ?>
            <a style="margin-left: 5px;" class="btn btn-sm btn-danger pull-right showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/delete', 'id' => $team->id]); ?>">
                <span class="glyphicon glyphicon-trash"></span> <?php echo \Yii::t('app', 'Delete Team'); ?>
            </a>
            <?php } if (\Yii::$app->user->can('editTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) {  ?>
            <a style="margin-left: 5px;" class="btn btn-sm btn-default pull-right showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/edit', 'id' => $team->id]); ?>">
                <span class="glyphicon glyphicon-pencil"></span> <?php echo \Yii::t('app', 'Edit Team'); ?>
            </a>
            <?php } ?>
            
            <?php if(\Yii::$app->user->can('inviteArtist',["team" => $team, "user" => \Yii::$app->user->identity])) { ?>
                <a style="margin-left: 5px;" class="btn btn-sm btn-default pull-right showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/invitecollaborator', 'id' => $team->id]); ?>">
                    <span class="glyphicon glyphicon-user"></span> <?php echo \Yii::t('app', 'invite artist'); ?>
                </a>
            <?php } if(\Yii::$app->user->can('inviteObserver',["team" => $team, "user" => \Yii::$app->user->identity])) { ?>
                <a style="margin-left: 5px;" class="btn btn-sm btn-default pull-right showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['team/inviteobserver', 'id' => $team->id]); ?>">
                    <span class="glyphicon glyphicon-user"></span> <?php echo \Yii::t('app', 'invite observer'); ?>
                </a>
            <?php } ?>

    
            <a class="btn btn-sm btn-default pull-right" href="<?php echo \Yii::$app->urlManager->createUrl(['team/view', 'id' => $team->id]); ?>">
                <span class="glyphicon glyphicon-eye-open"></span> <?php echo \Yii::t('app', 'View'); ?>
            </a>
            
            <h4 class="list-group-item-heading"><?= Html::encode($team->name); ?></h4>
            <p class="list-group-item-text"><?= \Yii::t('app', 'created') ?>: <?= Html::encode($team->creationtime) ?></p>
            <p class="list-group-item-text"><?= \Yii::t('app', 'Directors') ?>: 
            <?php $ar=array(); foreach($team->getDirectors() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s);
            ?></p>
            <p class="list-group-item-text"><?= \Yii::t('app', 'Artists') ?>: 
            <?php $ar=array(); foreach($team->getArtists() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s);
            ?></p>
            <p class="list-group-item-text"><?= \Yii::t('app', 'Observers') ?>: 
            <?php $ar=array(); foreach($team->getObservers() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s);
            ?></p>
            <p class="list-group-item-text"><?= \Yii::t('app', 'Description') ?>: <?= Html::encode($team->description); ?></p>

        </div>
    <?php } ?>
    </div>
</div>
