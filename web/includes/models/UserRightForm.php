<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class UserRightForm extends Model
{
    public $userid;
    public $teamid;
    public $right;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userid', 'teamid', 'right'], 'required']
        ];
    }
    
    public function __construct($user, $team) {
        $this->userid = $user->getId();
        $this->teamid = $team->getId();
    
        $r = $team->getRoleByUserid($user->getId());
        if($r!==false) {
            if($r==="0") $this->right=0;
            if($r==="1") $this->right=1;
            if($r==="2") $this->right=2;
        }        
    }


    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function userright()
    {
        if ($this->validate()) {
            return Team::editUserRole($this->userid,$this->teamid,$this->right);
        } else {
            return false;
        }
    }
}
