<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class kickuserForm extends Model
{

    public $confirm;
    public $userid;
    public $teamid;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userid','teamid','confirm'], 'required'],
            // verifyCode needs to be entered correctly
            ['confirm', 'validateConfirm']
        ];
    }
    
    /**
     * Validates the confirm checkbox
     * This method serves as the inline validation for password.
     */
    public function validateConfirm()
    {
        if($this->confirm !== "1") $this->addError('confirm', 'you need to confirm the kick');
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function kickUser()
    {
        if ($this->validate()) {
            $p = Team::findTeam($this->teamid);
            return $p->removeUser($this->userid);
        } else {
            return false;
        }
    }

}
