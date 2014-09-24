<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AddscreenplayForm extends Model
{
    public $name;
    public $teamid;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['name','teamid'], 'required']
        ];
    }

    /**
     * Validates the name.
     * This method serves as the inline validation for name.
     */
    public function validateName()
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
            return Screenplay::create($this->name,$this->teamid);
        } else {
            return false;
        }
    }
}
