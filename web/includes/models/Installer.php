<?php

namespace app\models;

class Installer
{
    private $error_occured = false;   
    private $modules = ["mysql", "pdo", "pdo_mysql", "simplexml", "zip"];
    private $permissions = null;
    
    public function __construct() {
        $this->permissions = [
            ["path" => \Yii::getAlias('@webroot')."/assets", "perm" => "w"],
            ["path" => \Yii::getAlias('@app')."/runtime", "perm" => "w"],
            ["path" => \Yii::getAlias('@app')."/config/db.php", "perm" => "w"],
            ["path" => \Yii::getAlias('@app')."/config/installed.php", "perm" => "w"],
            ["path" => \Yii::getAlias('@app')."/config/language.php", "perm" => "w"],
            ["path" => \Yii::getAlias('@app')."/config/mail.php", "perm" => "w"]
        ];
    }
    
    public function checkModules() {
        $returnarray = [];
        
        foreach($this->modules as $modul) {
            $returnarray[$modul] = extension_loaded($modul);
        }
        
        if(in_array(false, $returnarray)) {
            $this->error_occured = true;
        }
        
        return $returnarray;
    }
    
    
    public function checkPermissions() {
        $returnarray = [];
        
        foreach($this->permissions as $permission) {
            $returnarray[$permission["path"]] = $permission["perm"] === "w" && is_writable($permission["path"]);
        }
        
        if(in_array(false, $returnarray)) {
            $this->error_occured = true;
        }
        
        return $returnarray;
    }
    
    public function getErrorOccured() {
        return $this->error_occured;
    }
    
    public function setInstalled() {
        $installed = "<?php return true;";
        $byteswritten = file_put_contents(\Yii::getAlias('@app')."/config/installed.php", $installed);
        return $byteswritten;
    }

    public function setMailConfig($class, $host="", $username="", $password="", $port="", $encryption="") {
        if($class===null) {
        $mailfile = "<?php return [
            'class' => 'yii\swiftmailer\Mailer',
            ];
             ";
        } else {
        $mailfile = "<?php return [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => '" . $class . "',
                'host' => '". $host ."',
                'username' => '". $username ."',
                'password' => '". $password ."',
                'port' => '". $port ."',
                'encryption' => '". $encryption ."',
             ]];
             ";
        }
            $byteswritten = file_put_contents(\Yii::getAlias('@app')."/config/mail.php", $mailfile);
            if(!$byteswritten) throw new ErrorException(\Yii::t('app', 'no Bytes written'));
    }
    
    public function importDB($connection=null) {
        return $this->importSQL(\Yii::getAlias('@app')."/sawp.sql",$connection);
    }
    
    public function importSQL($path,$connection=null) {
        $sql = file_get_contents($path);
        try {
            $querys = $this->splitQuerys($sql);       
            foreach($querys as $query) {
                if($connection===null)
                    $command = \Yii::$app->db->createCommand($query);
                else
                    $command = $connection->createCommand($query);
                $result = $command->execute();
            } 

            return true;
        } catch (Exception $e) { 
            return false;
        }
    }
    
    public function isStringBoundaryChar($char){
        return $char=='\'' || $char=='"' || $char=='`';
    }
    
    /**
     * Splits the given query by semicolon characters ignoring semicolons inside strings and escaped semicolons.
     * @param type $sql the input string
     * @return array array of splited strings
     */
    public function splitQuerys($sql){
        $currentQuery="";
        $querys=array();
        $insideString=false;
        $escaped=false;
        $stringStartChar='';
        
        for($i=0;$i<strlen($sql);$i++){
            $char=$sql{$i};
            $currentQuery=$currentQuery.$char;
            
            //check if string starts or ends
            if($this->isStringBoundaryChar($char) && !$escaped){
                if($insideString){
                    if($char==$stringStartChar){
                        $insideString=false;
                    }
                }else{
                     $insideString=true;
                     $stringStartChar=$char;
                }
            }
            
            //check if query finished
            if($char==';' && !$insideString){
                array_push($querys,$currentQuery);
                $currentQuery="";
            }
            
            //check if next character is escaped
            if($char=='\\' && !$escaped){
                $escaped=true;
            }
            else{
                $escaped=false;
            }
        }
        return $querys;
    }

}