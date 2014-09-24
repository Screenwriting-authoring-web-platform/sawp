<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\EditprofileForm;
use app\models\User;
use yii\web\ForbiddenHttpException;

class UserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    public function actionEditprofile() {
        if (!\Yii::$app->user->can('editProfile')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new EditprofileForm();
        if ($model->load(Yii::$app->request->post()) && $model->editprofile()) {
            return $this->redirect(['user/view','id'=>\Yii::$app->user->identity->id]);
        } else {
            return $this->renderAjax('editprofile', ['model' => $model]);
        }
    }
    
    public function actionView($id) {
        if (!\Yii::$app->user->can('showProfile')) throw new ForbiddenHttpException('You are not allowed to access this page');
        $user = User::findIdentity($id);
        return $this->render('view', ['user' => $user]);
    }

}
