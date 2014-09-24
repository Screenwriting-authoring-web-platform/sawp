<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterinpublicForm extends Model
{
    public $teams;
    public $right;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['teams','right'], 'required']
        ];
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function addTeamsToUser()
    {
        if ($this->validate()) {
            foreach($this->teams as $id) {
                Team::findTeam($id)->addUser(\Yii::$app->user->identity->id,$this->right);
            }
            return true;
        } else {
            return false;
        }
    }
}
