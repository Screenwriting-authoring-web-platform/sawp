<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\models\AddteamForm;
use app\models\EditteamForm;
use app\models\TeamdeleteForm;
use app\models\TeamleaveForm;
use app\models\ImportForm;
use app\models\Team;
use app\models\InvitecollaboratorForm;
use app\models\InviteobserverForm;
use app\models\RegisterinpublicForm;
use app\models\UserRightForm;
use app\models\KickuserForm;
use app\models\User;
use yii\web\UploadedFile;

class TeamController extends Controller {

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
        if (!\Yii::$app->user->can('showTeamIndex')) throw new ForbiddenHttpException('You are not allowed to access this page');

        $model = \Yii::$app->user->identity;
        return $this->render('index', [
                    'model' => $model,
        ]);
            
    }

    public function actionAdd() {
        if (!\Yii::$app->user->can('createTeam')) throw new ForbiddenHttpException('You are not allowed to access this page');

        $model = new AddteamForm();

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            return $this->redirect(['team/index', 'ref' => 1]);
        } else {
            return $this->renderAjax('add', ['model' => $model]);
        }
    }

    public function actionView($id) {

        $model = Team::findOne($id);
        if (!\Yii::$app->user->can('viewTeam', ['team' => $model, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        return $this->render('view', ['model' => $model]);
    }

    public function actionEdit($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('editTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new EditteamForm($team);

        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            return $this->redirect(['team/index']);
        } else {
            return $this->renderAjax('edit', ['model' => $model,'team' => $team,]);
        }
    }

    public function actionDelete($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('deleteTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new TeamdeleteForm();

        if ($model->load(Yii::$app->request->post()) && $model->delete()) {
            return $this->redirect(['team/index']);
        } else {
            return $this->renderAjax('delete', ['model' => $model,'team' => $team,]);
        }
    }

    public function actionInvitecollaborator($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('inviteArtist', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new InvitecollaboratorForm();

        $invitableUsers = array_udiff(User::getUsers(), $team->getCollaborators(), function ($obj_a, $obj_b) {
            return $obj_a->id - $obj_b->id;
        }
        );
        $userlist = array();
        foreach ($invitableUsers as $user) {
            $userlist[$user->getId()] = $user->username;
        }

        if ($model->load(Yii::$app->request->post()) && $model->addUsersToTeam()) {
            return $this->redirect(['team/view', 'id' => $id]);
        } else {
            return $this->renderAjax('invitecollaborator', [
                        'model' => $model,
                        'team' => $team,
                        'userlist' => $userlist
            ]);
        }
    }

    public function actionInviteobserver($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('inviteObserver', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new InviteobserverForm();
        $invitableUsers = array_udiff(User::getUsers(), $team->getCollaborators(), function ($obj_a, $obj_b) {
            return $obj_a->id - $obj_b->id;
        }
        );
        $userlist = array();
        foreach ($invitableUsers as $user) {
            $userlist[$user->getId()] = $user->username;
        }

        if ($model->load(Yii::$app->request->post()) && $model->addObserversToTeam()) {
            return $this->redirect(['team/view', 'id' => $id]);
        } else {
            return $this->renderAjax('inviteobserver', [
                        'model' => $model,
                        'team' => $team,
                        'userlist' => $userlist
            ]);
        }
    }

    public function actionRegisterinpublic() {
        if (!\Yii::$app->user->can('registerInPublicTeam')) throw new ForbiddenHttpException('You are not allowed to access this page');

        $teams = Team::getPublicWhereUserIsNoCollaborator(\Yii::$app->user->identity->id);
        $model = new RegisterinpublicForm();

        if ($model->load(Yii::$app->request->post()) && $model->addTeamsToUser()) {
            return $this->redirect(['team/index']);
        } else {
            return $this->renderAjax('registerinpublic', [
                        'model' => $model,
                        'teams' => $teams,
            ]);
        }
    }

    public function actionKickuser($teamid, $userid) {
        $team = Team::findOne($teamid);
        if (!\Yii::$app->user->can('kickUserfromTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $user = User::findIdentity($userid);
        $model = new KickuserForm();

        if ($model->load(Yii::$app->request->post()) && $model->kickUser()) {
            return $this->redirect(['team/view', 'id' => $teamid]);
        } else {
            return $this->renderAjax('kickuser', [
                        'model' => $model,
                        'user' => $user,
                        'team' => $team,
            ]);
        }
    }

    public function actionSetuserright($teamid, $userid) {
        $team = Team::findOne($teamid);
        if (!\Yii::$app->user->can('setUsersTeamRole', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $user = User::findIdentity($userid);
        $model = new UserRightForm($user, $team);

        if ($model->load(Yii::$app->request->post()) && $model->userright()) {
            return $this->redirect(['team/view', 'id' => $teamid]);
        } else {
            return $this->renderAjax('userright', [
                        'model' => $model,
                        'user' => $user,
                        'team' => $team,
            ]);
        }
    }

    public function actionLeave($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('leaveTeam', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new TeamleaveForm();

        if ($model->load(Yii::$app->request->post()) && $model->leaveTeam()) {
            return $this->redirect(['team/index']);
        } else {
            return $this->renderAjax('leave', ['model' => $model,'team' => $team,]);
        }
    }
    
    public function actionImport($id) {
        $team = Team::findOne($id);
        if (!\Yii::$app->user->can('importFile', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new ImportForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->filecontent = UploadedFile::getInstance($model, 'filecontent');
            if($model->import()) return $this->redirect(['team/view', 'id' => $id]);
            else return $this->render('import',["model" => $model, "team" => $team]);
        } else {
            return $this->render('import',["model" => $model, "team" => $team]);
        }
    }

}
