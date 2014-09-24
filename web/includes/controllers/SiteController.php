<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Setting;


class SiteController extends Controller {

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

    public function actionIndex() {
        if(!\Yii::$app->params["installed"])
        return $this->redirect(['install/index']);
        
        return $this->render('index');
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    public function actionRegister() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->redirect(['site/registersuccess']);
        } else {
            
            $first = (count(User::getUsers())==0);
        
            return $this->render('register', ['model' => $model, 'first' => $first]);
        }
    }

    public function actionActivate($key) {
        if (User::validateMailToken($key)) {
            return $this->redirect(['team/index']);
        }
    }

    public function actionLogout() {
        $userid = \Yii::$app->user->getId();
        $command = \Yii::$app->db->createCommand("UPDATE screenplay SET locktime=null, lockuser=null WHERE lockuser = :userid");
        $command->bindValue(':userid', $userid);
        $post = $command->query();
    
        \Yii::$app->user->identity->updateLastActiveToPast();
    
        \Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        
        $mailaddresssetting = Setting::findSetting("contactmail");
        if($mailaddresssetting!=null && $mailaddresssetting->getValue()!=null && $mailaddresssetting->getValue()!="")
            $mailaddress = $mailaddresssetting->getValue();
        else {
            return $this->render('error', ['name' => \Yii::t('app', "Not found"),'message' => \Yii::t('app', "No contactaddress was found in database!")]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->contact($mailaddress)) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        } else {
            return $this->render('contact', ['model' => $model]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionRegistersuccess() {
        return $this->render('registersuccess');
    }

    public function init() {    
        $auth = Yii::$app->authManager;

        /*

          $perm = $auth->createPermission('AdminCreateTeam');
          $perm->description = 'Can create teams with another owner';
          $auth->add($perm);


          $reader = $auth->createRole('admin');
          $auth->add($reader);
          $auth->addChild($reader, $perm);

          $perm = $auth->createPermission('createTeam');
          $perm->description = 'create a new Team';
          $auth->add($perm);

          $show = $auth->createPermission('showTeamIndex');
          $show->description = 'show a list of teams the user is associated';
          $auth->add($show);

          $reader = $auth->createRole('user');
          $auth->add($reader);
          $auth->addChild($reader, $perm);
          $auth->addChild($reader, $show);

          $auth->assign($reader, 10);


          // add the rule
            //$rule = new \app\rbac\DirectorRule;
            //$auth->add($rule);

            $director = $auth->getRule("isDirector");
            $artist = $auth->getRule("isArtist");
            $observer = $auth->getRule("isOberserver");
            $artistorbetter = $auth->getRule("isArtistOrBetter");
            $observerorbetter = $auth->getRule("isObserverOrBetter");

            $role = $auth->getRole("user");
            //$perm = $auth->getPermission("removeCollaboratorFromTeam");


            $perm = $auth->createPermission('editPorfile');
            $perm->description = 'Permission to edit the profile';
            //$perm->ruleName = $artistorbetter->name;
            $auth->add($perm);
            // allow "author" to update their own posts
            $auth->addChild($role, $perm);          

            $perm = $auth->createPermission('showProfile');
            $perm->description = 'Permission to show a profile';
            //$perm->ruleName = $artistorbetter->name;
            $auth->add($perm);
            // allow "author" to update their own posts
            $auth->addChild($role, $perm);  
 */

    }

}

