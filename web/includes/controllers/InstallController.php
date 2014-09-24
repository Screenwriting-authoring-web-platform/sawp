<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\DbConfigForm;
use app\models\MailConfigForm;
use app\models\Installer;

class InstallController extends Controller {

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
        if(\Yii::$app->params["installed"])
        return $this->redirect(['site/index']);
        
        $this->layout='install';
        return $this->render('index');
    }

    public function actionTestmodules() {
        $model = new Installer();
    
        if(\Yii::$app->params["installed"])
        return $this->redirect(['site/index']);
        
        $this->layout='install';
        return $this->render('testmodules', ["model" => $model]);
    }

    public function actionDbconfig() {
        if(\Yii::$app->params["installed"])
        return $this->redirect(['site/index']);
        
        $this->layout='install';
        $model = new DbConfigForm();
        if ($model->load(Yii::$app->request->post()) && ($c=$model->setDbConfig())!==false) {
            //return $this->redirect(['install/importdb']);
            $installer = new Installer();
            return $this->render('importdb', ["model" => $installer, "connection" => $c]);
        } else {
            return $this->render('dbconfig', ["model" => $model]);
        }
    }

        public function actionMailconfig() {
        if(\Yii::$app->params["installed"])
        return $this->redirect(['site/index']);
        
        $this->layout='install';
        $model = new MailConfigForm();
        if ($model->load(Yii::$app->request->post()) && $model->setMailConfig()) {
            return $this->redirect(['install/dbconfig']);
        } else {
            return $this->render('mailconfig', ["model" => $model]);
        }
    }
}
