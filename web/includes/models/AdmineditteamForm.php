<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AdmineditteamForm extends Model
{
    public $name;
    public $description;
    public $teamid;
    public $ispublic;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['name','description','teamid','ispublic'], 'required'],
            // name is validated by validatePassword()
            ['name', 'validateName'],

        ];
    }
    
    public function __construct($team) {
        $this->name=$team->name;
        $this->description=$team->description;
        $this->ispublic=$team->isPublic();
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
    public function edit()
    {
        if ($this->validate()) {
            Team::edit($this->teamid,$this->ispublic,$this->name,$this->description);
            return true;
        } else {
            return false;
        }
    }
}
