<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\UserException;
use app\models\Setting;
/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword() {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate() && $this->userIsActive()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Validades whether the user is active
     *
     * @retun true if user is active false otherwise
     */
    public function userIsActive() {
        if($this->getUser()->status === 1) {
            return true;
        } else if($this->getUser()->status === 0) {
            if(Setting::findSetting("emailActivation")->getValue()) {
                throw new UserException(\Yii::t('app', 'Useraccount is not activated. Check your Mails.'));
            } else {
                return true;
            }    
        } else if($this->getUser()->status === 2) {
            throw new UserException(\Yii::t('app', 'Your Account was suspended. Contact your Administator if you think this is an error.'));
        } else {
            return false;
        }
        
    }
}
