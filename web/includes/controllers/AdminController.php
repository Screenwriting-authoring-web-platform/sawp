<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\UserdeleteForm;
use app\models\AdminaddteamForm;
use app\models\AdminAdduserForm;
use app\models\AdmineditteamForm;
use app\models\AdminedituserForm;
use app\models\AdminsettingsForm;
use app\models\User;
use app\models\Setting;
use app\models\TeamdeleteForm;
use app\models\Team;
use yii\web\ForbiddenHttpException;

class AdminController extends Controller {

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        if (!\Yii::$app->user->can('showAdminIndex')) throw new ForbiddenHttpException('You are not allowed to access this page');

        return $this->render('index');
    }

    public function actionUserlist() {
        if (!\Yii::$app->user->can('showUserlist')) throw new ForbiddenHttpException('You are not allowed to access this page');

        $users = User::getUsers();
        return $this->render('userlist', ['users' => $users]);
    }

    public function actionUserdelete($id) {
        if (!\Yii::$app->user->can('adminDeleteUser')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $user = User::findIdentity($id);
        $model = new UserdeleteForm();
        if ($model->load(Yii::$app->request->post()) && $model->delete()) {
            return $this->redirect(['admin/userlist']);
        } else {
            return $this->renderAjax('userdelete', ['model' => $model,'user' => $user]);
        }
    }

    public function actionTeamlist() {
        if (!\Yii::$app->user->can('showTeamlist')) throw new ForbiddenHttpException('You are not allowed to access this page');

        $teams = Team::getTeams();
        return $this->render('teamlist', [
                    'teams' => $teams,
        ]);
    }

    public function actionTeamdelete($id) {
        if (!\Yii::$app->user->can('adminDeleteTeam')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new TeamdeleteForm();
        $team = Team::findTeam($id);
        if ($model->load(Yii::$app->request->post()) && $model->delete()) {
            return $this->redirect(['admin/teamlist']);
        } else {
            return $this->renderAjax('teamdelete', [
                        'model' => $model,
                        'team' => $team
            ]);
        }
    }

    public function actionTeamadd() {
        if (!\Yii::$app->user->can('AdminCreateTeam')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new AdminaddteamForm();

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            return $this->redirect(['admin/teamlist']);
        } else {
            return $this->renderAjax('teamadd', [
                        'model' => $model,
                        'userlist' => User::getUsers()
            ]);
        }         
    }

    public function actionTeamedit($id) {
        if (!\Yii::$app->user->can('AdminEditTeam')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $team = Team::findOne($id);
        $model = new AdmineditteamForm($team);

        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            return $this->redirect(['admin/teamlist']);
        } else {
            return $this->renderAjax('teamedit', [
                        'model' => $model,
                        'team' => $team,
            ]);
        }
    }

    public function actionUseradd() {
        if (!\Yii::$app->user->can('AdminUserAdd')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new AdminAdduserForm();
        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            return $this->redirect(['admin/userlist']);
        } else {
            return $this->renderAjax('useradd', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUseredit($id) {
        if (!\Yii::$app->user->can('AdminUserEdit')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $user = User::findIdentity($id);
        $model = new AdminedituserForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->useredit()) {
            return $this->redirect(['admin/userlist']);
        } else {
            return $this->renderAjax('useredit', [
                        'model' => $model,
                        'user' => $user,
            ]);
        }
    }
    
    public function actionSettings() {
        if (!\Yii::$app->user->can('AdminSettings')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new AdminsettingsForm();

        if ($model->load(Yii::$app->request->post()) && $model->set()) {
            return $this->redirect(['admin/index']);
        } else {
            return $this->render('settings', [
                        'model' => $model
            ]);
        }
    }

}
