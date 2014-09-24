<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Installer;

class TestingController extends Controller{
    
    public $layout='none';
    
    
    public function actionResetTestingAccounts()
    {    
        $usersToDelete=['auto_testacc001'];
        
        foreach($usersToDelete as $username){
            $user=User::findByUsername($username);
            if($user!=null)
                $user->delete();
        }
        
        $status='ok';
        return $this->render('output',['status'=>$status]);
    }
    
    public function actionResetdb() {
        $model = new Installer();
        if($model->importDB())
            $status = "ok";
        else
            $status = "error";
        
        return $this->render('output',['status'=>$status]);
    }
    
    public function actionImportusers() {
        $model = new Installer();
        if($model->importSQL(\Yii::getAlias('@webroot')."/../tests/02_login/users.sql"))
            $status = "ok";
        else
            $status = "error";
        
        return $this->render('output',['status'=>$status]);
    }
    
    public function actionImportteams() {
        $model = new Installer();
        if($model->importSQL(\Yii::getAlias('@webroot')."/../tests/04_listteams/teams.sql"))
            $status = "ok";
        else
            $status = "error";
        return $this->render('output',['status'=>$status]);
    }
}