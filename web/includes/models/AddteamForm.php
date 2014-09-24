<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\UserException;

/**
 * LoginForm is the model behind the login form.
 */
class AddteamForm extends Model
{
    public $name;
    public $description;
    public $ispublic;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['name'], 'required'],
            // name is validated by validatePassword()
            [['name','ispublic','description'], 'dummyvalidation'],
        ];
    }

    /**
     * Validates the dummy.
     * This method serves as the inline validation for name.
     */
    public function dummyvalidation()
    {
    }


    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function add()
    {
        if ($this->validate()) {
            //throw new UserException("lol");
            return Team::create($this->ispublic,$this->name,$this->description);
        } else {
            return false;
        }
    }
}
