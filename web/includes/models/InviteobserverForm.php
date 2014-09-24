<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class InviteobserverForm extends Model
{
    public $teamid;
    public $observers;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['teamid','observers'], 'required']
        ];
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function addObserversToTeam()
    {
        if ($this->validate()) {
            $team = Team::findTeam($this->teamid);
            foreach($this->observers as $userid) {
                $team->addUser($userid, 2);
            }
            return true;
        } else {
            return false;
        }
    }
}
