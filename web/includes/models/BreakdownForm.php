<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class BreakdownForm extends Model
{
    public $screenplayid;
    public $type;
    public $scenes;
    public $graphic;
    public $categories;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['screenplayid','type'], 'required'],
            [['scenes','graphic','categories'], 'myValidation'],
            [['type'], 'myAtLeastOne']
        ];
    }
    
    public function myValidation() {
        
    }

    public function myAtLeastOne() {
        if($this->type==="1" || $this->type==="2") {
            if(!is_array($this->scenes) || count($this->scenes)===0) $this->addError('scenes', \Yii::t('app', "select at least one"));
            if(!is_array($this->categories) || count($this->categories)===0) $this->addError('categories', \Yii::t('app', "select at least one"));
        }
    }
    
    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function generate()
    {
        if ($this->validate()) {
            switch($this->type) {
                case(0): 
                    return false;
                    
                case(1): 
                    $type=1;
                    $input=["sc"=>$this->scenes,"ca"=>$this->categories,"gr"=>$this->graphic];
                    $data=Screenplay::generateBreakdownSceneCategory($this->screenplayid, $this->scenes, $this->categories);
                    return ["type"=>$type, "data" =>$data, "input"=>$input];
                    break;
                
                case(2): 
                    $type=2;
                    $input=["sc"=>$this->scenes,"ca"=>$this->categories,"gr"=>$this->graphic];
                    if($this->graphic) $data=Screenplay::generateBreakdownSceneCategory($this->screenplayid, $this->scenes, $this->categories);
                    else $data=Screenplay::generateBreakdownCategoryScene($this->screenplayid, $this->scenes, $this->categories);
                    return ["type"=>$type, "data" => $data, "input"=>$input];
                    break;
                    
                // 3 does not exists anymore
                
                case(4):
                    $type=4;
                    $input=["sc"=>$this->scenes,"ca"=>$this->categories,"gr"=>$this->graphic];
                    $data=Screenplay::generateBreakdownStatistics($this->screenplayid, $this->categories);
                    return ["type"=>$type, "data" => $data, "input"=>$input];
                    break;
                    
                default: 
                    return false;
            }
            return false;
        } else {
            return false;
        }
    }
}
