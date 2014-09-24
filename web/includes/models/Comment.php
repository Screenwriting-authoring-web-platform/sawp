<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;

class Comment extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'screenplay_comment';
    }
    
    public static function create($screenplayId, $text, $parentId=null) {
        $comment = new Comment();
        $comment->userId = \Yii::$app->user->identity->id;
        $comment->screenplayId = $screenplayId;
        $comment->text = $text;
        $comment->save();
        
        if($parentId!==null) {
            $parent = static::findComment($parentId);
            if($parent!==null) {
                $parent->nextId = $comment->getId();
                $parent->save();
            }
        }

        return $comment;
    }
    
    public static function getThread($anchor) {
        $comment = static::findComment($anchor);
        if($comment===null) return null;
        return $comment->getComments();
    }
    
    public function getComments() {
        if($this->nextId===null) {
            return [$this->getOutput()];
        } else {
            if( !(static::findComment($this->nextId) instanceof Comment) )
                return [$this->getOutput()];
            else
                return array_merge([$this->getOutput()], static::findComment($this->nextId)->getComments());
        }
    }
    
    public function getOutput() {
        $user = User::findIdentity($this->userId);
        $time = strtotime($this->creationtime);
        
        return [
            "id"=>$this->id,
            "text"=>$this->text,
            "creationtime"=>date("Y-m-d H:i", $time),
            "userId"=>$this->userId,
            "username"=>$user->username,
            "usericon"=>$user->get_gravatar(16)
        ];
    }
    
    public static function deleteThread($anchor) {
        $comment = static::findComment($anchor);
        if($comment===null) return null;
        $comments = $comment->getComments();
        if(!is_array($comments) || count($comments)==0) return false;
        
        foreach($comments as $c) {
            static::deleteComment($c["id"]);
        }
        return true;
    }
    
    public static function deleteComment($id) {
        $command = \Yii::$app->db->createCommand("DELETE FROM screenplay_comment WHERE id=:commentid");
        $command->bindValue(':commentid', $id);
        $post = $command->query(); 
    }

    /**
     * Get a comment with the given id
     *
     * @param id int
     * @return Comment|null
     */
    public static function findComment($id)
    {
        return Comment::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * Get the id of the Comment
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    

}
