<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class EditprofileForm extends Model
{
    public $password;
    public $newPassword;
    public $newPasswordRepeat;
    public $email;
    public $gravatarMailAddress;
    public $emailRepeat;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['password'], 'required'],
            // password is validated by validatePassword()
            [['newPassword','newPasswordRepeat'], 'validateNewPassword'],
            [['password'], 'validatePassword'],
            // email is validated by validateEmail()
            [['email','emailRepeat'], 'validateEmail'],
            [['gravatarMailAddress'], 'validateGravatarMailAddress'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validateNewPassword()
    {
        if(!($this->newPassword === $this->newPasswordRepeat)) $this->addError('newPasswordRepeat', 'password does not match.');
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = \Yii::$app->user->identity;

            if (!$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect password.');
            }
        }
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
     * Validates the email.
     * This method serves as the inline validation for email.
     */
    public function validateGravatarMailAddress()
    {
        if(!filter_var($this->gravatarMailAddress, FILTER_VALIDATE_EMAIL)) $this->addError('gravatarMailAddress', 'Incorrect email.');
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function editprofile()
    {
        if ($this->validate()) {
            $user = \Yii::$app->user->identity;
            if($this->email!="") $user->setMail($this->email);
            if($this->newPassword!="") $user->setPassword($this->newPassword);
            if($this->gravatarMailAddress!="") $user->setGravatarMailAddress($this->gravatarMailAddress);
            
            
            return true;
        } else {
            return false;
        }
    }
}
