<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class HistoryForm extends Model
{
    public $screenplayId;
    public $revision;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name is required
            [['screenplayId','revision'], 'required']
        ];
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function revert()
    {
        if ($this->validate()) {
            $screenplay = Screenplay::findScreenplay($this->screenplayId);
            return $screenplay->revertToRevision($this->revision);
        } else {
            return false;
        }
    }
}
