<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class InvitecollaboratorForm extends Model
{
    public $teamid;
    public $collaborators;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['teamid','collaborators'], 'required']
        ];
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function addUsersToTeam()
    {
        if ($this->validate()) {
            $team = Team::findTeam($this->teamid);
            foreach($this->collaborators as $userid) {
                $team->addUser($userid, 1);
            }
            return true;
        } else {
            return false;
        }
    }
}
