<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class TeamdeleteForm extends Model
{

    public $confirm;
    public $teamid;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['teamid', 'confirm'], 'required'],
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
        if($this->confirm !== "1") $this->addError('confirm', 'you need to confirm the deletion');
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function delete()
    {           
        if ($this->validate()) {
            $p = Team::findTeam($this->teamid);
            return $p->delete();
        } else return false;
    }

}
