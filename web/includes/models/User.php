<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\mail\BaseMessage;
use yii\base\UserException;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * @inheritdocstatic
     */
    public static function findIdentity($id)
    {
        return User::find()
            ->where(['id' => $id])->andWhere('status!=3')
            ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token)
    {
        return User::find()
            ->where(['accessToken' => $token])->andWhere('status!=3')
            ->one();
    }

    /**
     * Find user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::find()
            ->where(['username' => $username])->andWhere('status!=3')
            ->one();
    }

    public static function getLastActiveUsers() {
        return User::find()->where(['between', 'lastActive', date('Y-m-d H:i:s',time()-5*60), date('Y-m-d H:i:s',time())]);
    }

    /**
     * Register a new user
     *
     * @param  string      $username
     * @param  string      $email
     * @param  string      $password
     * @return true
     */
    public static function register($username,$email,$password)
    {
        $mailActivation = !(Setting::findSetting("emailActivation")->getValue());
        return static::newuser($username,$email,$password,'user',$mailActivation);
    }
    
    /**
     * Add new user (admin or internal use)
     *
     * @param  string      $username
     * @param  string      $email
     * @param  string      $password
     * @param  string      $rolename Name of the role (admin, user)
     * @param  boolean     $isactivated
     * @return true
     */
    public static function newuser($username,$email,$password,$roleName,$isactivated) {
        $newUser=new User();
        $newUser->username=$username;
        $newUser->mailAddress=$email;
        $newUser->passwordHash=\yii\helpers\Security::generatePasswordHash($password);
        $newUser->authKey=\yii\helpers\Security::generateRandomKey();
        $newUser->accessToken=\yii\helpers\Security::generateRandomKey();
        $newUser->status=$isactivated ? 1 : 0;
        $newUser->save();
        
        // Assign role user
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $auth->assign($role, $newUser->getId());

        if(!$isactivated) {
            //Generate Auth-token
            $token = \yii\helpers\Security::generateRandomKey();

            $command = \Yii::$app->db->createCommand("INSERT INTO user_mail_token (token, userid) VALUES (:token, :userid)");
                $command->bindValue(':token', $token);
                $command->bindValue(':userid', $newUser->getId());
                $post = $command->query();
            
            //Sent Activation Mail
            \Yii::$app->mail->compose()
            ->setTo($email)
            ->setSubject(Setting::findSetting("activationMailSubject")->getValue())
            ->setTextBody(strtr(Setting::findSetting("activationMailBody")->getValue(),[
                '{username}' => $username,
                '{link}' => \Yii::$app->urlManager->createAbsoluteUrl(['site/activate', 'key' => $token])
                ]))
            ->setFrom(Setting::findSetting("activationMailSender")->getValue())
            ->send();
        }
        return true;
    }
    
    /**
     * Activate User by Token (used in email link)
     * User gets automatically logged in if token is valid
     *
     * @param  string      $token
     * @param  boolean     $isactivated
     * @return true
     */
    public static function validateMailToken($token)
    {
        // Token abfragen
        $row = (new \yii\db\Query())
            ->select('*')
            ->from('user_mail_token')
            ->where('token=:token', [':token' => $token])
            ->limit(1)
            ->one();
        if($row !== false) {
            $command = \Yii::$app->db->createCommand("UPDATE user SET status = :status WHERE id = :id");
            $command->bindValue(':status', 1);
            $command->bindValue(':id',$row['userid']);
            $post = $command->query();

            $command = \Yii::$app->db->createCommand("DELETE FROM user_mail_token WHERE token = :token");
            $command->bindValue(':token', $token);
            $post = $command->query();
            \Yii::$app->user->login(User::findIdentity($row['userid']), 0);
            return true;
        } else {
            throw new UserException("Token not found");
        }
    }

    /**
     * Get an array of all users that are not deleted
     *
     * @return array|null
     */
    public static function getUsers()
    {
        return User::find()->where('status!=3')->all();
    }
       
    /**
     * Updates the last active Timestamp to the current time
     * 
     */
    public function updateLastActive() {
        $this->lastActive = date('Y-m-d H:i:s',time());
        $this->save();
    }
    
    /**
     * Updates the last active Timestamp to the current time-10 Minutes
     * 
     */
    public function updateLastActiveToPast() {
        $this->lastActive = date('Y-m-d H:i:s',time()-10*60);
        $this->save();
    }

    /**
     * checks wheter the user was active in the last 5 mintures
     * 
     * @return boolean true if the user was active, false otherwise
     */
    public function isActive() {
        return (strtotime($this->lastActive)+5*60>time());
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean true if password provided is valid for current user, false otherwise
     */
    public function validatePassword($password)
    {
        return Security::validatePassword($password, $this->passwordHash);
    }
    
    /**
     * Get an array of all teams the user is affiliate with
     *
     * @return array|null
     */
    public function getTeams()
    {
        $elements=$this->hasMany(Team::className(), ['id' => 'teamid'])
            ->viaTable('team_user', ['userid' => 'id']);
        $elements->via->where="team_user.rights=0";
        $a = $elements->Where("deleted != 1")->all();
        
        $elements=$this->hasMany(Team::className(), ['id' => 'teamid'])
            ->viaTable('team_user', ['userid' => 'id']);
        $elements->via->where="team_user.rights=1";
        $b = $elements->andWhere("deleted != 1")->all();
        
        $elements=$this->hasMany(Team::className(), ['id' => 'teamid'])
            ->viaTable('team_user', ['userid' => 'id']);
        $elements->via->where="team_user.rights=2";
        $c = $elements->andWhere("deleted != 1")->all();
        
        return array_merge($a,$b,$c);
    }
    
    /**
     * Get roles of the user (admin,user)
     *
     * @return array
     */
    public function getRole()
    {
       return \Yii::$app->authManager->getRolesByUser($this->id);
    }
    
    /**
     * Get status of the user
     *
     * @return int 0new, 1active/mail verified, 2banned, 3deleted
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Get array of all possible statuses
     *
     * @return int 0new, 1active/mail verified, 2banned, 3deleted
     */
    public static function getStatusArray()
    {
        $tmp = array();
        $tmp[0] = \Yii::t('app', 'new');
        $tmp[1] = \Yii::t('app', 'active');
        $tmp[2] = \Yii::t('app', 'banned');
        $tmp[3] = \Yii::t('app', 'deleted');
        return $tmp;
    }
    
    /**
     * Get name of status of user
     *
     * @return string
     */
    public function getStatusText()
    {
        switch($this->status) {
            case(0): return \Yii::t('app', 'new');
            case(1): return \Yii::t('app', 'active');
            case(2): return \Yii::t('app', 'banned');
            case(3): return \Yii::t('app', 'deleted');
            default: return \Yii::t('app', 'unknown');
        }
    }
        
    /**
     * deletes the user (set status=3)
     *
     */
    public function setDeleted()
    {
        $this->status = 3;
        $this->save();
    }
    
    /**
     * //todo remove this function (user can adminfunctions)
     */
    public function isAdmin() { //todo authmanager
        $a = array();
        foreach($this->getRole($this->id) as $i => $role) $a[]=$role->name;
        foreach($a as $right) if($right==="admin") return true;
        return false;
    }
    
    /**
     * set mail address of user
     *
     * @param string mail address
     */
    public function setMail($mail)
    {
        $this->mailAddress = $mail;
        $this->save();
    }
    
    /**
     * set gravatarmail address of user
     *
     * @param string mail address
     */
    public function setGravatarMailAddress($mail) {
        $this->gravatarMailAddress = $mail;
        $this->save();
    }
    
    /**
     * set password of user
     *
     * @param string pw password
     */
    public function setPassword($pw)
    {
        $this->passwordHash=\yii\helpers\Security::generatePasswordHash($pw);
        $this->save();
    }
    
    /**
     * set status of user
     *
     * @param int status
     */
    public function setStatus($status)
    {
        $this->status=$status;
        $this->save();
    }
    
    /**
     * set role of user
     *
     * @param string role
     */
    public function setRole($roleName) {
        $auth = \Yii::$app->authManager;
        $auth->revokeAll($this->getId());
        $role = $auth->getRole($roleName);
        $auth->assign($role, $this->getId());
    }
    
    /**
     * Edits the user with the given userid
     *
     * @param int userid
     * @param string email
     * @param string password
     * @param bool isadmin
     * @param int status
     * @return true
     */
    static function edit($userid, $email, $pw, $isadmin, $status) {
        $u = static::findIdentity($userid);
        $u->setMail($email);
        if($pw!=="") $u->setPassword($pw);
        if($isadmin) $u->setrole("admin"); else $u->setrole("user");
        $u->setstatus($status);
        return true;
    }
    
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    function get_gravatar($s = 80, $d = 'identicon', $r = 'x', $img = false, $atts = array() ) {
        if($this->gravatarMailAddress==null) $email = $this->mailAddress;
        else $email = $this->gravatarMailAddress;
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
    
}

\yii\base\Event::on(User::className(), ActiveRecord::EVENT_AFTER_FIND, function ($event) {
    if(isset(\Yii::$app->user) && isset(\Yii::$app->user->identity) && isset(\Yii::$app->user->identity->id) && ($event->sender->id === \Yii::$app->user->identity->id)) {
        \Yii::$app->user->identity->updateLastActive();
        \Yii::Info("Updated lastActive of User with id: ".$event->sender->id);
    }
});
