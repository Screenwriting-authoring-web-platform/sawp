<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $passwordRepeat;
    public $email;
    public $emailRepeat;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'passwordRepeat', 'email', 'emailRepeat'], 'required'],
            // password is validated by validatePassword()
            [['password','passwordRepeat'], 'validatePassword'],
            // email is validated by validateEmail()
            [['email','emailRepeat'], 'validateEmail'],
            // username is validated by validateUsername()
            ['username', 'validateUsername'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if(!($this->password === $this->passwordRepeat)) $this->addError('passwordRepeat', 'password does not match.');
    }
    
    /**
     * Validates the username.
     * This method serves as the inline validation for username.
     */
    public function validateUsername()
    {
        if(User::findByUsername($this->username) !== null) $this->addError('username', 'username already taken.');
    }
    
    /**
     * Validates the email.
     * This method serves as the inline validation for email.
     */
    public function validateEmail()
    {
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) $this->addError('email', 'Incorrect email.');
        if(!($this->email === $this->emailRepeat)) $this->addError('emailRepeat', 'email does not match.');
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function register()
    {
        if ($this->validate()) {
            return User::register($this->username,$this->email,$this->password);
        } else {
            return false;
        }
    }
}
