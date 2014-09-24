<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\Nav;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\Team $model
 */
$this->title = \Yii::t('app', 'Team list');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administration'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-userlist">
    <h1><?= Html::encode($this->title); ?></h1>
    
    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('app', 'Teams'); ?> 
        
        <a class="btn btn-sm btn-success showinmodal" href="<?= \Yii::$app->urlManager->createUrl(['admin/teamadd']); ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= \Yii::t('app', 'Add new team'); ?>
        </a>
        
        </div>
    
        <!-- Table -->
        <table class="table">
            <tr>
                <th><?= Yii::t('app', '#'); ?></th>
                <th><?= Yii::t('app', 'id'); ?></th>
                <th><?= Yii::t('app', 'Name'); ?></th>
                <th><?= Yii::t('app', 'Description'); ?></th>
                <th><?= Yii::t('app', 'Directors'); ?></th>
                <th><?= Yii::t('app', 'Artists'); ?></th>
                <th><?= Yii::t('app', 'Observers'); ?></th>
                <th><?= Yii::t('app', 'Public'); ?></th>
                <th><?= Yii::t('app', 'Options'); ?></th>
            </tr>
            <?php foreach($teams as $i => $team) { ?>
                <tr>
                    <td><?= Html::encode($i); ?></td>
                    <td><?= Html::encode($team->getId()); ?></td>
                    <td><?= Html::encode($team->name); ?></td>
                    <td><?= Html::encode($team->description); ?></td>
                    <td><?php $ar=array(); foreach($team->getDirectors() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s); ?></td>
                    <td><?php $ar=array(); foreach($team->getArtists() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s); ?></td>
                    <td><?php $ar=array(); foreach($team->getObservers() as $c) $ar[]=$c->username; $s=implode(", ",$ar); echo Html::encode($s); ?></td>
                    <td><?= $team->isPublic() ? Yii::t('app', 'public') : Yii::t('app', 'private'); ?></td>
                    <td>
                        <a class="btn btn-default btn-sm showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['admin/teamedit', 'id' => $team->id ]); ?>" ><?= Yii::t('app', 'edit'); ?></a>
                        <a class="btn btn-danger btn-sm showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['admin/teamdelete', 'id' => $team->id ]); ?>" ><?= Yii::t('app', 'delete'); ?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>



    </ul>

</div>
