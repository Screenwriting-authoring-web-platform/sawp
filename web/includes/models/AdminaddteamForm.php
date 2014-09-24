<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AdminaddteamForm extends Model
{
    public $name;
    public $ispublic;
    public $ownerid;
    public $description;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['name','description','ownerid','ispublic'], 'required'],
            // name is validated by validatePassword()
            ['ownerid', 'validateUserid'],

        ];
    }
    
    /**
     * Validates the name.
     * This method serves as the inline validation for name.
     */
    public function validateUserid()
    {
        return true; //todo
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function add()
    {
        if ($this->validate()) {
            return Team::admincreate($this->ownerid,$this->ispublic,$this->name,$this->description);
        } else {
            return false;
        }
    }
}
