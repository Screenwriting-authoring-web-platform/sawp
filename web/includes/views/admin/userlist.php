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
$this->title = \Yii::t('app', 'User list');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administration'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-userlist">
    <h1><?= Html::encode($this->title); ?></h1>
    
    <a class="btn btn-sm btn-success showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['admin/useradd']); ?>">
        <span class="glyphicon glyphicon-plus"></span> <?php echo \Yii::t('app', 'Add new user'); ?>
    </a>
    
    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('app', 'Users'); ?></div>
    
        <!-- Table -->
        <table class="table">
            <tr>
                <th><?= Yii::t('app', '#'); ?></th>
                <th><?= Yii::t('app', 'Name'); ?></th>
                <th><?= Yii::t('app', 'Email'); ?></th>
                <th><?= Yii::t('app', 'role'); ?></th>
                <th><?= Yii::t('app', 'status'); ?></th>
                <th><?= Yii::t('app', 'options'); ?></th>
            </tr>
            <?php foreach($users as $i => $user) { ?>
                <tr>
                    <td><?= Html::encode($i); ?></td>
                    <td><?= Html::encode($user->username); ?></td>
                    <td><?= Html::encode($user->mailAddress); ?></td>
                    <td><?php 
                    $a = array();
                    foreach($user->getRole($user->id) as $i => $role) $a[]=$role->name;
                    $b = implode($a,", ");
                    echo Html::encode($b);
                    
                    ?></td>
                    <td><?= Html::encode($user->getStatusText()); ?></td>
                    <td>
                        <a class="btn btn-default btn-sm showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['admin/useredit', 'id' => $user->id ]); ?>" ><?= Yii::t('app', 'edit'); ?></a>
                        <a class="btn btn-danger btn-sm showinmodal" href="<?php echo \Yii::$app->urlManager->createUrl(['admin/userdelete', 'id' => $user->id ]); ?>" ><?= Yii::t('app', 'delete'); ?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>



    </ul>

</div>
