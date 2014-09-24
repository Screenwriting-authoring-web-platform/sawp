<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AdminedituserForm extends Model
{
    public $userid;
    public $password;
    public $passwordRepeat;
    public $email;
    public $emailRepeat;
    public $isadmin;
    public $status;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userid', 'email', 'emailRepeat', 'isadmin'], 'required'],
            // password is validated by validatePassword()
            [['password','passwordRepeat'], 'validatePassword'],
            // email is validated by validateEmail()
            [['email','emailRepeat'], 'validateEmail'],
            // username is validated by validateUsername()
            ['isadmin', 'validateIsAdmin'],
            ['status', 'validateStatus']
        ];
    }
    
    public function __construct($user) {
        $this->email=$user->mailAddress;
        $this->emailRepeat=$user->mailAddress;
        $this->isadmin=$user->isAdmin();
        $this->status=$user->getStatus();
    }

    public function validateIsAdmin()
    {
        if(($this->isadmin !== "1") && ($this->isadmin !== "0")) $this->addError('isadmin', 'error');
    }
    
    public function validateStatus()
    {
        if(!(($this->status === "0") || ($this->status === "1") || ($this->status === "2") || ($this->status === "3"))) $this->addError('status', 'error');
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
    public function useredit()
    {
        if ($this->validate()) {
            return User::edit($this->userid,$this->email,$this->password,$this->isadmin,$this->status);
        } else {
            return false;
        }
    }
}
