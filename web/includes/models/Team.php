<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use app\models\Setting;

class Team extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'team';
    }
    
    /**
     * @inheritdoc
     */
    public static function findTeam($id)
    {
        return Team::find()
            ->where(['id' => $id])
            ->andWhere("deleted != 1")
            ->one();
    }

    /**
     * Finds team by teamname
     *
     * @param  string      $teamname
     * @return static|null
     */
    public static function findByTeamname($teamname)
    {
        return Team::find()
            ->where(['teamname' => $teamname])
            ->andWhere("deleted != 1")
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get an array of all Teams
     * @return array|null
     */    
    public static function getTeams()
    {
        return Team::find()->Where("deleted != 1")->all();
    }
    
    /**
     * Create a team where the current user becomes the director.
     *
     * @param ispublic boolean public if true
     * @param name string name of the new team
     * @param description string description of the new team
     * @return true on success, false otherwise
     */ 
    public static function create($ispublic,$name, $description) {
        Team::admincreate(\Yii::$app->user->identity->id,$ispublic,$name,$description);
        return true;
    }
    
    /**
     * Creates a new team with the given user becomes the director
     *
     * @param ownerid int Id of the user that becomes the director
     * @param ispublic boolean public if true
     * @param name string name of the new team
     * @param description string description of the new team
     * @return true on success, false otherwise
     */
    public static function admincreate($ownerid, $ispublic, $name, $description) {
        $newTeam = new Team();
        $newTeam->name= $name;
        $newTeam->description = $description;
        $newTeam->setPublic($ispublic);
        $newTeam->defaultCategories = Setting::findSetting("defaultCategories")->getValue();
        $newTeam->save();
        
        $newTeam->addUser($ownerid, 0);
        
        return true;
    }
    
    /**
     * Get all collaborators (direcors,artists,observers) in the current team
     *
     * @return array of userIds|null
     */
    public function getCollaborators()
    {
        $elements=$this->hasMany(User::className(), ['id' => 'userid'])
            ->viaTable('team_user', ['teamid' => 'id']);
        return $elements->all();
    }
    
    /**
     * Get all directors in the current team
     *
     * @return array of userIds|null
     */
    public function getDirectors()
    {
        $elements=$this->hasMany(User::className(), ['id' => 'userid'])
            ->viaTable('team_user', ['teamid' => 'id']);
        $elements->via->where="team_user.rights=0";
        return $elements->all();
    }
    
    /**
     * Get all artists in the current team
     *
     * @return array of userIds|null
     */
    public function getArtists()
    {
        $elements=$this->hasMany(User::className(), ['id' => 'userid'])
            ->viaTable('team_user', ['teamid' => 'id']);
        $elements->via->where="team_user.rights=1";
        return $elements->all();
    }
    
    /**
     * Get all observers in the current team
     *
     * @return array of userIds|null
     */
    public function getObservers()
    {
        $elements=$this->hasMany(User::className(), ['id' => 'userid'])
            ->viaTable('team_user', ['teamid' => 'id']);
        $elements->via->where="team_user.rights=2";
        return $elements->all();
    }
    
    /**
     * Get the id of the role the given user has in the team
     *
     * @return id(0 director, 1 artist, 2 observer)
     */
    public function getRoleByUserid($id)
    {
        $query = new Query;
        // compose the query
        $query->select('rights')
            ->from('team_user')
            ->where(['teamid'=>intval($this->getId()),'userid'=>$id])
            ->limit(1);
        // build and execute the query
        $row = $query->one();
        
        if($row===false) return false;
        else return $row["rights"];
    }
    
    /**
     * Get the screenplays in the team
     *
     * @return array|null
     */
    public function getScreenplays()
    {
        $elements=$this->hasMany(Screenplay::className(), ['teamid' => 'id']);
        return $elements->Where("deleted != 1")->all();
    }
    
    /**
     * Add a User to the team with the given role
     *
     * @param userid int the id of the user to add
     * @param role int (0 director, 1 artist, 2 observer)
     */
    public function addUser($userid, $role)
    {
        $command = \Yii::$app->db->createCommand("INSERT INTO team_user (`teamid`, `userid`, `rights`) VALUES (:teamid, :userid, :rights)");
        $command->bindValue(':teamid', intval($this->id));
        $command->bindValue(':userid', $userid);
        $command->bindValue(':rights', $role);
        $post = $command->query();
    }
    
    /**
     * Remove a User from the team
     *
     * @param userid int the id of the user to remove
     * @return true
     */
    public function removeUser($userid)
    {
        $command = \Yii::$app->db->createCommand("DELETE FROM team_user WHERE userid=:userid AND teamid=:teamid");
        $command->bindValue(':teamid', intval($this->id));
        $command->bindValue(':userid', $userid);
        $post = $command->query();
        return true;
    }
    
    /**
     * Get the public state of the team
     *
     * @return true if team is public, false otherwise
     */
    public function isPublic() {
        return $this->public;
    }
    
    /**
     * Set the public state of the team
     *
     * @param isboblic bool true if team is public, false otherwise
     */
    public function setPublic($ispublic) {
        $this->public=$ispublic;
        $this->save();
    }
    
    /**
     * Set the name of the team
     *
     * @param name string
     */
    public function setName($name) {
        $this->name=$name;
        $this->save();
    }
    
    /**
     * Set the description of the team
     *
     * @param description string
     */
    public function setDescription($description) {
        $this->description=$description;
        $this->save();
    }
    
    /**
     * Set the default categories of the team
     *
     * @param defaultCategories string
     */
    public function setDefaultCategories($defaultCategories) {
        $this->defaultCategories=$defaultCategories;
        $this->save();
    }
    
    /**
     * Get the default categories of the team
     *
     * @return defaultCategories string
     */
    public function getDefaultCategories() {
        return $this->defaultCategories;
    }
    
    /**
     * Set the name,description and public state at once of the team
     *
     * @param id int id of the team to edit
     * @param ispublic bool
     * @param name string
     * @param description string
     */
    static function edit($id,$ispublic,$name,$description,$defaultCategories) {
        $p = static::findTeam($id);
        $p->setPublic($ispublic);
        $p->setName($name);
        $p->setDescription($description);
        $p->setDefaultCategories($defaultCategories);
    }
    
    /**
     * Get all Teams that are public and the given user is no collaborateur
     *
     * @param userid int id of the user
     * @return array|null
     */
    static public function getPublicWhereUserIsNoCollaborator($userid) {
        return Team::find()->where(["public"=>true])->andWhere("deleted != 1")->all(); //todo and user no member (in sql, see views/registerinpublic.php)
    }
    
    /**
     * Get an array with roleid => rolename
     *
     * @return array
     */
    static function getRoleArray() {
        $tmp = array();
        $tmp[0] = \Yii::t('app', 'Director');
        $tmp[1] = \Yii::t('app', 'Artist');
        $tmp[2] = \Yii::t('app', 'Observer');
        return $tmp;
    }
    
    /**
     * In the given team change the role of the given user to the given role
     *
     * @param userid int 
     * @param teamid int
     * @param roleid int (0 director, 1 artist, 2 observer)
     * @return true
     */
    static function editUserRole($userid,$teamid,$roleid) {
        $command = \Yii::$app->db->createCommand("UPDATE team_user set rights=:rights WHERE teamid=:teamid AND userid=:userid");
        $command->bindValue(':teamid', $teamid);
        $command->bindValue(':userid', $userid);
        $command->bindValue(':rights', $roleid);
        $post = $command->query();       
        return true;
    }

    /**
     * set a team deleted
     *
     */
    public function delete()
    {       
        $command = \Yii::$app->db->createCommand("UPDATE team SET deleted=1 WHERE id = :teamid");
        $command->bindValue(':teamid', intval($this->getId()));
        return $post = $command->query();
    }
}
