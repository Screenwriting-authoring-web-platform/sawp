<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Html;

class Screenplay extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'screenplay';
    }
    
    /**
     * @inheritdoc
     */
    public static function findScreenplay($id)
    {
        return Screenplay::find()
            ->where(['id' => $id])
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
     * Get the id of the team the screenplay belongs to
     *
     * @return int teamid
     */
    public function getTeamId()
    {
        return $this->teamid;
    } 
    
    /**
     * Saves the given text as a new revision of the screenplay
     *
     * @param string text
     */
    public function saveRevision($text) {

        if($text === $this->getLastRevision())
            return ["status"=>"unchanged"];

        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_text_revision (`text`) VALUES (:text)");
        $command->bindValue(':text', $text);
        $post = $command->query();
        $textId = \Yii::$app->db->getLastInsertID();
        
        $command = \Yii::$app->db->createCommand("SELECT treeId FROM `screenplay_revision` WHERE screenplayId=:screenplayid ORDER BY `creation_time` DESC LIMIT 1");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $post = $command->query();
        $row = $post->read();
        $treeId = $row["treeId"];
        
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_revision (`screenplayId`, `textId`, `treeId`) VALUES (:screenplayid, :textId, :treeId)");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $command->bindValue(':textId', $textId);
        $command->bindValue(':treeId', $treeId);
        $post = $command->query();
        
        return ["status"=>"ok"];
    }
    
    /**
     * Get the last revision of the screenplay
     *
     * @return string screenplay revision
     */
    public function getLastRevision() {
        $command = \Yii::$app->db->createCommand("SELECT text FROM screenplay_revision, screenplay_text_revision WHERE screenplay_text_revision.id=textId AND screenplayId=:screenplayid ORDER BY `creation_time` DESC LIMIT 1");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $post = $command->query();
    
        $row = $post->read();
        if($row===false) \Yii::error("Databaseerror");
        
        return $row["text"];
    }
    
    /**
     * Get the last revision of the screenplay without p's that have class note
     *
     * @return string screenplay revision
     */
    public function getLastRevisionWithoutNotes() {
        //<p class="note"></p>
        return preg_replace('#<p class="note">(.*?)</p>#', '', $this->getLastRevision());
    }
    
    /**
     * Get the given revision of the screenplay
     *
     * @return string screenplay revision
     */
    public function getRevision($rid) {
        $command = \Yii::$app->db->createCommand("SELECT text FROM screenplay_revision, screenplay_text_revision WHERE screenplay_text_revision.id=textId AND screenplayId=:screenplayid AND screenplay_revision.id=:rid");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $command->bindValue(':rid', intval($rid));
        $post = $command->query();
    
        $row = $post->read();
        if($row===false) \Yii::error("Databaseerror");
        
        return $row["text"];
    }
    
    public function getTreeRevision($rid) {
        $command = \Yii::$app->db->createCommand("SELECT content FROM screenplay_revision, screenplay_tree_revision WHERE screenplay_tree_revision.id=treeId AND screenplayId=:screenplayid AND screenplay_revision.id=:rid");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $command->bindValue(':rid', intval($rid));
        $post = $command->query();
    
        $row = $post->read();
        if($row===false) \Yii::error("Databaseerror");
        
        return $row["content"];
    }
    
    public function revertToRevision($rid) {
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_tree_revision (`content`) VALUES (:text)");
        $command->bindValue(':text', $this->getTreeRevision($rid));
        $post = $command->query();
        $treeId = \Yii::$app->db->getLastInsertID();
        
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_text_revision (`text`) VALUES (:text)");
        $command->bindValue(':text', $this->getRevision($rid));
        $post = $command->query();
        $textId = \Yii::$app->db->getLastInsertID();

        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_revision (`screenplayid`, `textId`, `treeId`) VALUES (:screenplayid, :textId, :treeId)");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $command->bindValue(':textId', $textId);
        $command->bindValue(':treeId', $treeId);
        return $command->query();
    }
    
    /**
     * Saves the given Tree as a new revision of the screenplay
     *
     * @param string content
     */
    public function saveTree($content) {

        if($content == $this->getTree())
            return ["status"=>"unchanged"];

        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_tree_revision (`content`) VALUES (:content)");
        $command->bindValue(':content', $content);
        $post = $command->query();
        $treeId = \Yii::$app->db->getLastInsertID();
        
        $command = \Yii::$app->db->createCommand("SELECT textId FROM `screenplay_revision` WHERE screenplayId=:screenplayid ORDER BY `creation_time` DESC LIMIT 1");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $post = $command->query();
        $row = $post->read();
        $textId = $row["textId"];
        
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_revision (`screenplayId`, `textId`, `treeId`) VALUES (:screenplayid, :textId, :treeId)");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $command->bindValue(':textId', $textId);
        $command->bindValue(':treeId', $treeId);
        $post = $command->query();
        return ["status"=>"ok"];
    }
    
    /**
     * Get the current Tree of the screenplay
     *
     * @return string content
     */
    public function getTree() {
        $command = \Yii::$app->db->createCommand("SELECT content FROM screenplay_revision, screenplay_tree_revision WHERE screenplay_tree_revision.id=treeId AND screenplayId=:screenplayid ORDER BY `creation_time` DESC LIMIT 1");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $post = $command->query();
    
        $row = $post->read();
        if($row===false) \Yii::error("Databaseerror");
        
        return $row["content"];
    }

    /**
     * Create a new screenplay
     *
     * @param string name
     * @param int teamid
     * @return bool true
     */
    public static function create($name, $teamid, $text="", $tree=false) {
        $newScreenplay = new Screenplay();
        $newScreenplay->name= $name;
        $newScreenplay->teamid= $teamid;
        $newScreenplay->save();
        $team = Team::findTeam($teamid);
        
        if(!$tree) {
            if($team->getDefaultCategories()==null || $team->getDefaultCategories()=="") {
                $dbDefTree='{"expanded":true,"key":"root_1","title":"root","children":[{"expanded":true,"folder":true,"key":"_1","selected":false,"title":"ttt","data":{"color":"#d06b64"},"children":[{"expanded":true,"folder":true,"key":"_2","selected":false,"title":"Characters","tooltip":"click the edit button to edit the categories","children":[{"expanded":false,"folder":true,"key":"_3","selected":false,"title":"good Guys","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_4","selected":false,"title":"bad Guys","tooltip":"click the edit button to edit the categories"}]},{"folder":true,"key":"_5","selected":false,"title":"VFX","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_6","selected":false,"title":"Set","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_7","selected":false,"title":"Props","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_8","selected":false,"title":"Sound","tooltip":"click the edit button to edit the categories"},{"folder":true,"key":"_9","selected":false,"title":"Music","tooltip":"click the edit button to edit the categories"}]}]}';
                $defTree = json_decode($dbDefTree);
            } else {        
                $defTree = json_decode($team->getDefaultCategories()); 
            }
            $defTree->children[0]->title = Html::encode($name);
            $treeJSON = json_encode($defTree);
        } else {
            $treeJSON = $tree;
        }
        
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_tree_revision (`content`) VALUES (:text)");
        $command->bindValue(':text', $treeJSON);
        $post = $command->query();
        $treeId = \Yii::$app->db->getLastInsertID();
        
        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_text_revision (`text`) VALUES (:text)");
        $command->bindValue(':text', $text);
        $post = $command->query();
        $textId = \Yii::$app->db->getLastInsertID();

        $command = \Yii::$app->db->createCommand("INSERT INTO screenplay_revision (`screenplayid`, `textId`, `treeId`) VALUES (:screenplayid, :textId, :treeId)");
        $command->bindValue(':screenplayid', intval($newScreenplay->getId()));
        $command->bindValue(':textId', $textId);
        $command->bindValue(':treeId', $treeId);
        $post = $command->query();
        
        return true;
    }
        
    /**
     * set a screenplay deleted
     *
     */
    public function delete()
    {       
        $command = \Yii::$app->db->createCommand("UPDATE screenplay SET deleted=1 WHERE id = :screenplayid");
        $command->bindValue(':screenplayid', intval($this->getId()));
        return $post = $command->query();
    }
    
    /**
     * return an array with keys=revisionId and value=dateTime
     * only revisions with distinct textId
     *
     * return array
     */
    public function getRevisionHeaders() {
        $command = \Yii::$app->db->createCommand("SELECT id, creation_time FROM (SELECT textId, id, creation_time FROM screenplay_revision WHERE screenplayId=:screenplayid ORDER BY `creation_time` DESC) as tmp GROUP BY textId ");
        $command->bindValue(':screenplayid', intval($this->getId()));
        $post = $command->query();
    
        $rows = $post->readAll();
        if($rows===false) \Yii::error("Databaseerror");
        
        return $rows;
    }
    
    /**
     * Get the team the screenplay belongs to
     *
     * @return team
     */
    public function getTeam() {
        return Team::findTeam($this->teamid);
    }

    /**
    * Locks the current screenplay to the currently logged in user
    *
    * @return true if success, false otherwise
    */
    public function lock() {
        $this->locktime = date('Y-m-d H:i:s',time());
        $this->lockuser = \Yii::$app->user->identity->id;
        \Yii::$app->user->identity->updateLastActive();
        return $this->save();
    }

    /**
    * Checks wether the current screenplay is locked
    *
    * @return int id of the user which has the lock, false otherwise
    */
    public function isLocked() {
        if(strtotime($this->locktime)+60*5 < time() || $this->lockuser === null) {
            return false;
        } else {
            return $this->lockuser;
        }
    }

    /**
    * Adds the commentanchor to the scriptcontent
    *
    * @var integer Position where to put the anchor
    * @return boolean true if successfull, false otherwise
    */
    public function addCommentAnchor($pos, $threadId) {
        $currenRevision = $this->getLastRevision();
        $insertString = "<a name=\"".$threadId."\"></a>";
        $newRevision = substr($currenRevision, 0, $pos) . $insertString . substr($currenRevision, $pos);
        $this->saveRevision($newRevision);
    }


    /**
     * Get the scenes of the screenplay
     *
     * @return array
     */
    public function getScenes() {
        $ReturnArray = array();
        $htmlin=$this->getLastRevision();
        $html = "<body>".$htmlin."</body>";
        $xml = static::getSimpleXMLElementFromHtml($html);
        
        foreach ($xml->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) static::strip_only_tags($p->asXML());
            if($class=="" || $text=="") continue;
            
            if($class=="scene") {
                $scenename = mb_strtoupper($text, 'UTF-8');
                $ReturnArray[] = $scenename;
            }
        }
        return $ReturnArray;
        
    /*
        $text = $this->getLastRevision();
        $output_array = array();
        preg_match_all("/.*?<p class=\"scene\">(.*?)<\/p>/m", $text, $output_array);
        $temp = array_map('html_entity_decode', array_values($output_array[1]));
        $temp2 = array();
        foreach($temp as $t) $temp2[] = mb_strtoupper(static::strip_only_tags($t),'UTF-8');
        return $temp2;
    */
    }
    
    /**
     * Get the characters of the screenplay
     *
     * @return array
     */
    public function getCharacters() {
        $text = $this->getLastRevision();
        $output_array = array();
        preg_match_all("/.*?<p class=\"character\">(.*?)<\/p>/m", $text, $output_array);
        $temp = array_map('html_entity_decode', array_values(array_unique($output_array[1])));
        $temp2 = array();
        foreach($temp as $t) {
            if($t=="&NBSP;") continue;
            $character = mb_strtoupper(static::strip_only_tags($t),'UTF-8');
            $temp2[$character] = $character;
        }
        return $temp2;
    }
    
    /**
     * Get the categories (of tags) of the screenplay
     *
     * @return array
     */
    public function getCategories() {
        $treePlain = $this->getTree();
        $tree = json_decode($treePlain);

        function rec($deep,$subtree) {
            if(isset($subtree->folder) && $subtree->folder===false) return array();

            $element = array();
            $element["deep"] = $deep;
            $element["title"] = $subtree->title;
            $element["key"] = "category".$subtree->key;
            
            $nt = array();
            $nt["category".$subtree->key] = $element;

            if(isset($subtree->children) && is_array($subtree->children) && count($subtree->children)>0) {
                foreach($subtree->children as $child) $nt = array_merge($nt, rec($deep+1,$child));
            }
            
            return $nt;
        }
        
        $newtree = rec(-1,$tree);
        unset($newtree["categoryroot_1"]);

        return $newtree;
    }
    
    /**
     * Get the tags that are in a category
     *
     * @return array
     */
    public function getTagsFromCategories($categories) {
        $treePlain = $this->getTree();
        $tree = json_decode($treePlain);

        function rec2($subtree,$categories) {
            if(isset($subtree->folder) && $subtree->folder===false) return array();

            $nt = array();
            
            if(in_array("category".$subtree->key, $categories) && isset($subtree->children) && is_array($subtree->children) && count($subtree->children)>0) {
                foreach($subtree->children as $c) {
                    if(isset($c->folder) && $c->folder===false) {
                        $e = ["title"=>isset($c->title) ? $c->title : "","category"=>isset($subtree->title) ? $subtree->title : "","key"=>"category".$c->key];
                        $nt["category".$c->key] = $e;
                    }
                }                
            }

            if(isset($subtree->children) && is_array($subtree->children) && count($subtree->children)>0)
            foreach($subtree->children as $child) $nt = array_merge($nt, rec2($child,$categories));
            
            return $nt;
        }
        
        $newtree = rec2($tree,$categories);
        return $newtree;
    }
    
    /**
     * Get the Style information for the tags (css)
     *
     * @return string
     */
    public function getCssFromAllTags() {
        $treePlain = $this->getTree();
        $tree = json_decode($treePlain);

        function rec2($subtree) {
            if(isset($subtree->folder) && $subtree->folder===false) return array();

            $nt = array();
            
            if(isset($subtree->children) && is_array($subtree->children) && count($subtree->children)>0) {
                foreach($subtree->children as $c) {
                    if(isset($c->folder) && $c->folder===false && isset($c->data) && isset($c->data->color)) {
                        $nt["category".$c->key] = $c->data->color;
                    }
                }                
            }

            if(isset($subtree->children) && is_array($subtree->children) && count($subtree->children)>0)
            foreach($subtree->children as $child) $nt = array_merge($nt, rec2($child));
            
            return $nt;
        }
        
        $newtree = rec2($tree);
        $css = "";
        
        foreach($newtree as $categoryname => $color) {
            $css.="span.".$categoryname." {\n    background-color: ".$color.";\n}";
        }
        
        return $css;
    }
    
    public static function getSimpleXMLElementFromHtml($html) {
        //save < and >
        $html = str_replace("<","șșș",$html);
        $html = str_replace(">","ȔȔȔ",$html);
    
        //replace &ouml; with ö
        $html = html_entity_decode($html);
        
        //undo <>&
        $html = str_replace("<","&lt;",$html);
        $html = str_replace(">","&gt;",$html);
        $html = str_replace("&","&amp;",$html);
        
        //load < and >
        $html = str_replace("șșș","<",$html);
        $html = str_replace("ȔȔȔ",">",$html);
    
        return new \SimpleXMLElement($html);
    }
    
    public static function getTextFromXML($text) {
        return HTML::decode(HTML::decode($text));
    }
    
    public static function insertPagebreakavoiddivInHtml($html) {

        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        
        $i = -1;
        foreach($dom->getElementsByTagName("p") as $p) {
            $i++;
            if(!$p->hasAttribute("class")) continue;
            if(!($p->getAttribute("class")=="character")) continue;
            $next = $dom->getElementsByTagName("p")->item($i+1);
            if($next==null) continue;
            if(!($next->getAttribute("class")=="dialogue")) continue;
            
            $parent = $p->parentNode;
            $div = $parent->insertBefore($dom->createElement("div"),$p);
            $div->setAttribute("style","page-break-inside: avoid;");
            $parent->removeChild($p);
            $parent->removeChild($next);
            
            $div->appendChild($p);
            $div->appendChild($next);
        }
        
        $i = -1;
        foreach($dom->getElementsByTagName("p") as $p) {
            $i++;
            if(!$p->hasAttribute("class")) continue;
            if(!($p->getAttribute("class")=="scene")) continue;

            $next = $p->nextSibling;
            if($next==null) continue;
            while($next!=null && ($next instanceof \DOMText)) {
                $next = $next->nextSibling;
            }
            
            if($next==null) continue;
            if($next instanceof \DOMText) continue;
            
            if($next->getAttribute("class")=="scene") continue;

            $parent = $p->parentNode;
            $div = $parent->insertBefore($dom->createElement("div"),$p);
            $div->setAttribute("style","page-break-inside: avoid;");
            $parent->removeChild($p);
            $parent->removeChild($next);
            
            $div->appendChild($p);
            $div->appendChild($next);
        }

        $htmlout = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
        $htmlout = str_replace("</body>","",$htmlout);
        $htmlout = str_replace("<body>","",$htmlout);
        $htmlout = str_replace("\n\n","\n",$htmlout);
        $htmlout = str_replace("\n\n","\n",$htmlout);
        $htmlout = str_replace("\n\n","\n",$htmlout);
        $htmlout = str_replace("\n\n","\n",$htmlout);
        $htmlout = trim($htmlout);
        return $htmlout;
    }
    
    /**
     * Generate the breakdownreport scenes to categories
     *
     * @param int screenplayid id of the screenplay
     * @param array scenes the scenes that should be contained in the report
     * @param array categories the categories should be contained in the report
     * @return array the Breakdownreport
     */
    public static function generateBreakdownSceneCategory($screenplayId, $scenes, $categories) {        
        function myInArray($needle, $array) {
            if($array==null || !is_array($array) || count($array)==0) return false;
            foreach($array as $e) {
                if($e["key"]==$needle) return $e;
            }
            return false;
        }
        
        $sp = Screenplay::findScreenplay($screenplayId);
        $allCategories = $sp->getCategories();
        $tags = $sp->getTagsFromCategories($categories);
        
        $ReturnArray = array();
        
        $htmlin=$sp->getLastRevision();
        $html = "<body>".$htmlin."</body>";
        $xml = static::getSimpleXMLElementFromHtml($html);
        $currentScene = null;
        $currentSceneNo = 0;
        
        foreach ($xml->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) static::strip_only_tags($p->asXML());
            if($class=="" || $text=="") continue;
            
            if($class=="scene") {
                $scenename = mb_strtoupper($text, 'UTF-8');
                if(in_array($currentSceneNo, $scenes)) {
                    $currentScene = $currentSceneNo;
                    
                    $ReturnArray[] = ["name"=>($currentScene+1).": ".$scenename, "tags"=>[]];
                }
                else $currentScene = null;
                $currentSceneNo++;
                continue;
            }
            
            if($currentScene === null) continue;    //important: use === instead of == because 0 == null is true
            foreach ($p->span as $span) {
                $spanclass = ($span['class']==NULL) ? "" : ((string) $span['class']);
                $spantext = ($span==NULL) ? "" : (string) static::strip_only_tags(static::getTextFromXML($span->asXML()));
                
                if($spanclass=="" || $spantext=="" || !($e=myInArray($spanclass,$tags))) continue;
                
                $ReturnArray[count($ReturnArray)-1]["tags"][$e["category"]."/".$e["title"]] = $spantext;
            }
        }
        return $ReturnArray;
    }
    
    /**
     * Generate the breakdownreport categories to scenes
     *
     * @param int screenplayid id of the screenplay
     * @param array scenes the scenes that should be contained in the report
     * @param array categories the categories should be contained in the report
     * @return array the Breakdownreport
     */
    public static function generateBreakdownCategoryScene($screenplayId, $scenes, $categories) {        
        function myInArray($needle, $array) {
            if($array==null || !is_array($array) || count($array)==0) return false;
            foreach($array as $e) {
                if($e["key"]==$needle) return $e;
            }
            return false;
        }

        $sp = Screenplay::findScreenplay($screenplayId);
        $allCategories = $sp->getCategories();
        $tags = $sp->getTagsFromCategories($categories);
        
        $ReturnArray = array();
        
        $htmlin=$sp->getLastRevision();
        $html = "<body>".$htmlin."</body>";
        $xml = static::getSimpleXMLElementFromHtml($html);
        $currentScene = null;
        $currentSceneNo = 0;
        
        foreach ($xml->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) static::strip_only_tags($p->asXML());
            if($class=="" || $text=="") continue;
            
            if($class=="scene") {
                $scenename = mb_strtoupper($text, 'UTF-8');
                if(in_array($currentSceneNo, $scenes)) {
                    $currentScene = $currentSceneNo;
                }
                else $currentScene = null;
                $currentSceneNo++;
                continue;
            }
            
            if($currentScene === null) continue;
            foreach ($p->span as $span) {
                $spanclass = ($span['class']==NULL) ? "" : ((string) $span['class']);
                $spantext = ($span==NULL) ? "" : (string) static::strip_only_tags(static::getTextFromXML($span->asXML()));
                
                if($spanclass=="" || $spantext=="" || !($e=myInArray($spanclass,$tags))) continue;
                
                if(!isset($ReturnArray[$spantext])) {
                    $ReturnArray[$spantext]=["title"=>"(".$e["category"]."/".$e["title"].") ".$spantext,"scenes"=>[$currentScene]];
                } else {
                    if(!in_array($currentScene,$ReturnArray[$spantext]["scenes"]))
                        $ReturnArray[$spantext]["scenes"][]=$currentScene;
                }
                
            }
        }
        return $ReturnArray;
    }

    /**
     * Generate the breakdownreport statistics
     *
     * @param int screenplayid id of the screenplay
     * @return array the Breakdownreport
     */
    public static function generateBreakdownStatistics($screenplayId, $categories) {
        $sp = Screenplay::findScreenplay($screenplayId);
        $tagscount = 0;
        if(is_array($categories)) $tagscount = count($sp->getTagsFromCategories($categories));
        
        $htmlin=$sp->getLastRevision();
        $html = "<body>".$htmlin."</body>";
        $xml = static::getSimpleXMLElementFromHtml($html);
        $currentScene = null;
        
        $ReturnArray = ["words"=>str_word_count(static::strip_only_tags($htmlin)),"characters"=>$tagscount,"pcount"=>[],"wordcount"=>[]];
        
        foreach ($xml->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) static::strip_only_tags($p->asXML());
            if($class=="" || $text=="") continue;

            if(!isset($ReturnArray["pcount"][$class])) $ReturnArray["pcount"][$class]=1;
            else $ReturnArray["pcount"][$class]++;
            
            if(!isset($ReturnArray["wordcount"][$class])) $ReturnArray["wordcount"][$class]=str_word_count($text);
            else $ReturnArray["wordcount"][$class]+=str_word_count($text);
        }
        return $ReturnArray;
    }
    
    /**
     * remove all tags
     *
     */
    static function strip_only_tags($in) {
        $regex  = '/<\/?[a-zA-Z0-9=\s\"\._]+\/?>/';
        return preg_replace($regex,'',$in);
    }
}
