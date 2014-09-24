<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\AddscreenplayForm;
use app\models\ScreenplaydeleteForm;
use app\models\HistoryForm;
use app\models\Team;
use app\models\Screenplay;
use app\models\BreakdownForm;
use app\models\ExportForm;
use app\models\ExportPhpWord;

class ScreenplayController extends Controller{

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
    
    public function actionShoweditor($id)
    {
        $screenplay = Screenplay::findScreenplay($id);
        $screenplay->lock();
        $team = $screenplay->getTeam();
        $isObserver = !\Yii::$app->user->can('saveScreenplayContent', ['team' => $team, 'user' => \Yii::$app->user->identity]);
        if (!\Yii::$app->user->can('showEditor', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
            $this->layout='screenplay';
        return $this->render('edit',["screenplay" => $screenplay, 'isObserver' => $isObserver]);
    }
    
    public function actionDelete($id)
    {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('deleteScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new ScreenplaydeleteForm();
        $teamid = $screenplay->getTeam()->id;

        if ($model->load(Yii::$app->request->post()) && $model->deleteScreenplay($id)) {
            return $this->redirect(['team/view','id'=>$teamid]);
        } else {
            return $this->renderAjax('delete', [
                'model' => $model,
                'screenplay' => $screenplay,
            ]);
        }
    }
    
    public function actionAdd($id)
    {
        $team = Team::findTeam($id);
        if (!\Yii::$app->user->can('addScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new AddscreenplayForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            return $this->redirect(['team/view', 'id' => $id]);
        } else {
            return $this->renderAjax('add', [
                'model' => $model,
                'team' => $team
            ]);
        }
    }
    
    public function actionExport($teamid, $screenplayid) {
        $model = new ExportForm();
        $screenplay = Screenplay::findScreenplay($screenplayid);
        if ($model->load(Yii::$app->request->post()) && ($url=$model->export())) {
            return $this->redirect($url);
        } else {
            return $this->renderAjax('export', ['model' => $model,'screenplay' => $screenplay]);
        }
    }
    
    private function getHtml($model,$notes) {
        if($notes=="1" || intval($notes)==1) 
            $html = $model->getLastRevision(); 
        else
            $html = $model->getLastRevisionWithoutNotes();
            
            $html = Screenplay::insertPagebreakavoiddivInHtml($html);
            
        return $html;
    }
    
    public function actionExporthtml($id, $tags, $notes)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('exportScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $this->layout='none';
        $filename = preg_replace('/[^a-zA-Z0-9]/','', $model->name).".html";
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $tagcss="";
        if($tags=="1" || intval($tags)==1) $tagcss=$model->getCssFromAllTags();
        $html = $this->getHtml($model,$notes);
        
        return $this->render('exporthtml',["html" => $html, "name" => $model->name, "box" => true, "tagcss"=>$tagcss]);
    }
    
    public function actionExportpdf($id, $tags, $notes)
    {
        require(Yii::getAlias('@vendor').'/dompdf/dompdf_config.inc.php');
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('exportScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $this->layout='none';
        $filename = preg_replace('/[^a-zA-Z0-9]/','', $model->name).".pdf";
        $tagcss="";
        if($tags=="1" || intval($tags)==1) $tagcss=$model->getCssFromAllTags();
        $html = $this->getHtml($model,$notes);  
        
        $dompdf = new \DOMPDF();
        $dompdf->load_html($this->render('exporthtml',["html" => $html, "name" => $model->name, "box" => false, "tagcss"=>$tagcss]));
        $dompdf->set_paper("a4", 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, array("Attachment" => 1));
        return;
    }

    public function actionExportdocx($id, $notes)
    {
        
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('exportScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $this->layout='none';
        $filename = preg_replace('/[^a-zA-Z0-9]/','', $model->name).".docx";
        if($notes=="1" || intval($notes)==1) $html = $model->getLastRevision(); else $html = $model->getLastRevisionWithoutNotes();

        ExportPhpWord::exportPhpWord($model->name,$html,$filename, "Word2007");
        return;
    }
    
    public function actionExportodt($id, $notes)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('exportScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $this->layout='none';
        $filename = preg_replace('/[^a-zA-Z0-9]/','', $model->name).".odt";
        if($notes=="1" || intval($notes)==1) $html = $model->getLastRevision(); else $html = $model->getLastRevisionWithoutNotes();

        ExportPhpWord::exportPhpWord($model->name,$html,$filename, "ODText");
        return;
    }
    
    public function actionExportrtf($id, $notes)
    {
        $model = Screenplay::findScreenplay($id);
        $team = $model->getTeam();
        if (!\Yii::$app->user->can('exportScreenplay', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $this->layout='none';
        $filename = preg_replace('/[^a-zA-Z0-9]/','', $model->name).".rtf";
        if($notes=="1" || intval($notes)==1) $html = $model->getLastRevision(); else $html = $model->getLastRevisionWithoutNotes();

        ExportPhpWord::exportPhpWord($model->name,$html,$filename, "RTF");
        return;
    }
    
    public function actionBreakdownreport($id)
    {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('generateBreakdown', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new BreakdownForm();

        if ($model->load(Yii::$app->request->post()) && (($ret=$model->generate())!==false)) {
            return $this->render('showbreakdownreport', ['breakdowndata' => $ret, "screenplay" => $screenplay]);
        } else {
            return $this->render('breakdownreport',["model" => $model, "screenplay" => $screenplay]);
        }
    }
    
    public function actionHistory($id)
    {
        $screenplay = Screenplay::findScreenplay($id);
        $team = $screenplay->getTeam();
        if (!\Yii::$app->user->can('showHistory', ['team' => $team, 'user' => \Yii::$app->user->identity])) throw new ForbiddenHttpException('You are not allowed to access this page');
        $model = new HistoryForm();

        if ($model->load(Yii::$app->request->post()) && $model->revert()) {
            return $this->redirect(['showeditor','id'=>$id]);
            //http://localhost/web/index.php?r=screenplay/edit&id=2
        } else {
            return $this->render('history',["model" => $model, "screenplay" => $screenplay]);
        }
    }
}