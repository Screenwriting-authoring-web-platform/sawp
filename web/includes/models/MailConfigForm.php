<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class MailConfigForm extends Model
{
    public $mailhost;
    public $mailport;
    public $mailusername;
    public $mailpassword;
    public $mailencryption;
    public $mailusesmtp;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mailusesmtp'], 'validateMyrequired'],
            [['mailencryption','mailusesmtp','mailhost','mailport','mailusername','mailpassword'], 'validateDummy'],
            [['mailport'],'validateNumeric'],
        ];
    }
    
    public function validateDummy() {}
    
    public function validateMyrequired() {
        if($this->mailusesmtp==="0") return;
        if($this->mailhost=="") $this->addError('mailhost', "required");
        if($this->mailport=="") $this->addError('mailport', "required");
        if($this->mailusername=="") $this->addError('mailusername', "required");
        if($this->mailpassword=="") $this->addError('mailpassword', "required");
    }
    
    public function validateNumeric() {
        if(!ctype_digit($this->mailport))
        $this->addError('mailport', \Yii::t('app', 'only digits allowed!'));
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function setMailConfig()
    {
        if ($this->validate()) {
            
            $installer = new Installer();
            if($this->mailusesmtp)
                $installer->setMailConfig("Swift_SmtpTransport", $this->mailhost, $this->mailusername, $this->mailpassword, $this->mailport, $this->mailencryption);
            else
                $installer->setMailConfig(null);
            return true;
        } else {
            return false;
        }
    }
}
