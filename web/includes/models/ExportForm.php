<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class ExportForm extends Model
{
    public $screenplayid;
    public $format;
    public $tags;
    public $notes;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['screenplayid','format'], 'required'],
            [['tags','notes'], 'myValidation']
        ];
    }
    
    public function myValidation() {
        
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function export()
    {
        if ($this->validate()) {
            switch($this->format) {
                case(1): $url = Yii::$app->urlManager->createUrl(['/screenplay/exporthtml', 'id' => $this->screenplayid, "tags"=>($this->tags === "1"), "notes"=>($this->notes === "1")]); break;
                case(2): $url = Yii::$app->urlManager->createUrl(['/screenplay/exportpdf', 'id' => $this->screenplayid, "tags"=>($this->tags === "1"), "notes"=>($this->notes === "1")]); break;
                case(3): $url = Yii::$app->urlManager->createUrl(['/screenplay/exportdocx', 'id' => $this->screenplayid, "notes"=>($this->notes === "1")]); break;
                case(4): $url = Yii::$app->urlManager->createUrl(['/screenplay/exportodt', 'id' => $this->screenplayid, "notes"=>($this->notes === "1")]); break;
                case(5): $url = Yii::$app->urlManager->createUrl(['/screenplay/exportrtf', 'id' => $this->screenplayid, "notes"=>($this->notes === "1")]); break;
                default: return false; break;
            }
            return $url;
        } else {
            return false;
        }
    }
}
