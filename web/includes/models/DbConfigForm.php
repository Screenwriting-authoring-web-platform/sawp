<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class DbConfigForm extends Model
{
    public $username;
    public $password;
    public $server;
    public $database;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'server', 'database'], 'required'],
            ['password', 'validatepassword']
        ];
    }

    public function validatepassword() {
        
    }

    /**
     * Registers a user using the provided username, password and emailadress.
     * @return boolean whether the user is registered successfully
     */
    public function setDbConfig()
    {
        if ($this->validate() && $this->testDbConfig()) {
            return $this->writeDbConfig();
        } else {
            return false;
        }
    }
    
    private function writeDbConfig() {
        $dbcon = "<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=$this->server;dbname=$this->database',
    'username' => '$this->username',
    'password' => '$this->password',
    'charset' => 'utf8',
];";
        $byteswritten = file_put_contents(Yii::getAlias('@app')."/config/db.php", $dbcon);
        if(!$byteswritten) {
            $this->addError('server', "Config could not be written");
            return false;
        }
        
        $array = [
            'dsn' => 'mysql:host='.$this->server.';dbname='.$this->database.'',
            'username' => $this->username,
            'password' => $this->password,
            'charset' => 'utf8',
        ];
        $connection = new \yii\db\Connection($array);
        return $connection;
    }
    
    private function testDbConfig() {
        try {
            $db = new \PDO('mysql:host='.$this->server.';dbname='.$this->database.';charset=UTF8', $this->username, $this->password);
            return true;
        } catch (\PDOException $e) {
            $errorCode = $e->getCode();
            if($errorCode === 1045) {
                $this->addError('password', 'Incorrect username or password.');
                $this->addError('username', 'Incorrect username or password.');
            } else if($errorCode === 1044) {
                $this->addError('password', 'Incorrect username or password.');
                $this->addError('username', "Incorrect username or password.");
            } else if($errorCode === 1049) {
                $this->addError('database', "Can't access Database.");
            } else if($errorCode === 2005) {
                $this->addError('server', "Can't connect to Server.");
            }
            else {
                $this->addError('server', "General Error: ".$e->getMessage());
            }
            return false;
        }
    }
}
