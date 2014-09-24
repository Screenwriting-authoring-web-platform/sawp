<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CeltxImport;
use app\models\TrelbyImport;

/**
 * LoginForm is the model behind the login form.
 */
class ImportForm extends Model
{
    public $teamId;
    public $filecontent;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['teamId','filecontent'], 'required'],
            [['filecontent'], 'file']
        ];
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function import()
    {
        if ($this->validate()) {
            
            $extension = mb_strtolower(pathinfo($this->filecontent->name,PATHINFO_EXTENSION),'UTF-8');
            $path = $this->filecontent->tempName;
            
            switch($extension) {
                case("celtx"):
                    return CeltxImport::import($this->teamId, $path);
                case("trelby"):
                    return TrelbyImport::import($this->teamId, $path);
                default: 
                    $this->addError("filecontent","Unsupportet fileformat");
                    break;
            }
        } else {
            return false;
        }
    }
}
