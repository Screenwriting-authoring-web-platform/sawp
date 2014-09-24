<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class EditteamForm extends Model
{
    public $name;
    public $description;
    public $teamid;
    public $ispublic;
    public $defaultCategories;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['name','teamid','ispublic','defaultCategories'], 'required'],
            // name is validated by validatePassword()
            ['name', 'validateName'],
            ['description','dummy']

        ];
    }
    
    public function __construct($team) {
        $this->name=$team->name;
        $this->description=$team->description;
        $this->ispublic=$team->isPublic();
        $this->defaultCategories=$team->getDefaultCategories();
    }

    /**
     * Validates the name.
     * This method serves as the inline validation for name.
     */
    public function validateName()
    {
        return true; //todo
    }
    
    public function dummy()
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
            Team::edit($this->teamid,$this->ispublic,$this->name,$this->description,$this->defaultCategories);
            return true;
        } else {
            return false;
        }
    }
}
