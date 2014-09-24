<?php

namespace app\models;

class CeltxImport
{
    /**
     * remove all tags
     *
     */
    static function strip_only_tags($in) {
        $regex  = '/<.*?>/';
        return preg_replace($regex,'',$in);
    }
    
    public static function import($teamid, $path) {
        $za = new \ZipArchive();

        $za->open($path);
        $folder = $path."_extracted/";
        mkdir($folder);
        $za->extractTo($folder);
    
        $colors = ["#d06b64","#f83a22","#fa573c","#ff7537","#ffad46","#42d692","#16a765","#7bd148","#b3dc6c","#fbe983","#fad165","#92e1c0","#9fe1e7","#9fc6e7","#4986e7","#9a9cff","#b99aff","#c2c2c2","#cabdbf","#cca6ac","#f691b2","#cd74e6","#a47ae2"];
        $doc = new \DOMDocument();
        
        $files = scandir($folder);
        $filename = "";
        foreach($files as $file) {
            if(substr($file,0,7)=="script-") {
                $filename = $file;
                break;
            }
        }
        if($filename=="") return false;
        
        $filecontent = html_entity_decode(file_get_contents($folder.$filename));
        $doc->loadHTML($filecontent);
        $xml = simplexml_import_dom($doc);

        $title = (string) $xml->head->title;
        
        $html = "";
        $tree = [];
        
        foreach ($xml->body->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) static::strip_only_tags($p->asXML());
            if($text=="" || $text=="\n") continue;
            
            $tagged = [];
            
            foreach ($p->span as $span) { //here not perfect
                $urlparts = explode("/", $span["ref"]);
                $id = end($urlparts);
                $cat = (string) $span["class"];
                $content = (string) $span;
                $tree[$cat][$id] = $content;

                if(in_array($content,$tagged)) continue;
                $text = str_replace($content,'<span class="category'.$id.'">'.$content.'</span>',$text);
                $tagged[] = $content;
            }
                        
            switch($class) {
                case("sceneheading"):    $html.='<p class="scene">'.$text.'</p>'."\n"; break;
                case("transition"):      $html.='<p class="transition">'.$text.'</p>'."\n"; break;
                case("shot"):            $html.='<p class="shot">'.$text.'</p>'."\n"; break;
                case("action"):          $html.='<p class="action">'.$text.'</p>'."\n"; break;
                case("parenthetical"):   $html.='<p class="parenthetical">'.mb_substr($text,1,mb_strlen($text)-2).'</p>'."\n"; break;
                case("dialog"):          $html.='<p class="dialogue">'.$text.'</p>'."\n"; break;
                case("character"):       $html.='<p class="character">'.$text.'</p>'."\n"; break;
                default: continue; break;
            }
        }

        $i=1;
        $defaultTree = static::getDefaultArray($title);
        foreach($tree as $cat => $child) {
            $roundcolor = $colors[$i % count($colors)];
            $category = (array(
               'children' => array (),
               'data' => (array('color' => $roundcolor)),
               'expanded' => false,
               'folder' => true,
               'key' => '_i'+$i++,
               'selected' => false,
               'title' => $cat,
               'tooltip' => 'click the edit button to edit the categories',
            ));
            
            foreach($child as $id => $content) {
                $category["children"][] = (array(
                   'data' => (array('color' => $roundcolor)),
                   'folder' => false,
                   'key' => $id,
                   'selected' => false,
                   'title' => $content,
                ));
            
            }
            $defaultTree["children"][0]["children"][] = $category;            
        }
        Screenplay::create($title, $teamid, $html, json_encode($defaultTree));
        return true;
    }
    
    public static function getDefaultArray($title) {
$data = (array(
   'children' => 
  array (
    0 => 
    (array(
       'children' => [],
       'expanded' => true,
       'folder' => true,
       'key' => '_1',
       'selected' => false,
       'title' => $title,
    )),
  ),
   'expanded' => true,
   'key' => 'root_1',
   'selected' => false,
   'title' => 'root',
));
        return $data;
    }
}
