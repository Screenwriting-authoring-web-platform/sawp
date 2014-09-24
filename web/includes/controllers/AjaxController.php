<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Team;
use app\models\Screenplay;
use app\models\Comment;
use yii\web\ForbiddenHttpException;

class AjaxController extends Controller{

    public $enableCsrfValidation = false;

    public function actions()
    {
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
    
    public function actionSavescreenplaycontent($id)
    {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('saveScreenplayContent', ['team' => $team, 'user' => \Yii::$app->user->identity]))
                $status = ["status"=>"fail,rights"];
        else
            $status = $screenplay->saveRevision($_POST["content"]);

        return $this->renderAjax('save',["status" => $status]);
    }
    
    public function actionGetscreenplaycontent($id)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('getScreenplayContent', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        return $this->renderAjax('get',["content" => $model->getLastRevision()]);
    }
    
    public function actionGetscreenplaycontentbyrevision($id,$rid)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('getScreenplayContent', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        return $this->renderAjax('get',["content" => $model->getRevision($rid)]);
    }
    
    public function actionSavetree($id) {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('saveScreenplayTree', ['team' => $team, 'user' => \Yii::$app->user->identity]))
            $status = ["status"=>"fail,rights"];
        else
            $status = $screenplay->saveTree($_POST["content"]);
        return $this->renderAjax('save',["status" => $status]);
    }
    
    public function actionGettree($id)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('getScreenplayTree', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        return $this->renderAjax('get',["content" => $model->getTree()]);
    }
    
    public function actionGetthread($id) { 
        $commentScreenplayId = Comment::findComment($id)->screenplayId;
        $screenplay = Screenplay::findScreenplay($commentScreenplayId);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('viewComment', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        return $this->renderAjax('get',["content" => json_encode(Comment::getThread($id))]);
    }
    
    public function actionCreatecomment() {
        $model = Screenplay::findScreenplay($_POST["screenplayId"]);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('createComment', ['team' => $team, 'user' => \Yii::$app->user->identity])) $status = ["status" => "fail,rights"];
        else {
            $comment = Comment::create($_POST["screenplayId"], $_POST["text"], $_POST["parentId"]);
            if(isset($_POST["pos"])) {
                $model->addCommentAnchor($_POST["pos"],$comment->id);
            }
            $status = ["status"=>"ok","commentId"=>$comment->id];
        }
        return $this->renderAjax('save',["status" => $status]);
    }
    
    public function actionDeletethread($id) {
        $commentScreenplayId = Comment::findComment($id)->screenplayId;
        $screenplay = Screenplay::findScreenplay($commentScreenplayId);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('deleteComment', ['team' => $team, 'user' => \Yii::$app->user->identity])) $status = ["status" => "fail,rights"];
        else {
            if(Comment::deleteThread($id)) $status = ["status" => "ok"];
            else $status = ["status" => "fail"];
        }
        return $this->renderAjax('get',["content" => json_encode($status)]);
    }

    public function actionKeeplock($id) {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('createComment', ['team' => $team, 'user' => \Yii::$app->user->identity]) &&
            !\Yii::$app->user->can('saveScreenplayTree', ['team' => $team, 'user' => \Yii::$app->user->identity])
        ) $status = ["status" => "fail,rights"];
        else { 
            if($screenplay->lock()) $status = ["status" => "ok"];
            else $status = ["status" => "fail"];
        }
        return $this->renderAjax('save',["status" => $status]);
    }
}