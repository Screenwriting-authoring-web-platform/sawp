<?php

namespace app\models;

class TrelbyImport
{    
    public static function import($teamid, $path) {
        $file = fopen($path, "r");
        
        if ($file) {
            $script = "";
            $title;
            $nextLineNewElement = true;
        
            while (($line = fgets($file)) !== false) {
                $line = trim($line);
                if($line=="#Start-Script") {
                    $script="";
                    $nextLineNewElement = true;
                }
                
                if(!isset($title) && mb_substr($line,0,13)=="#Title-String") {
                    $titleparts = explode(",",$line);
                    $title = $titleparts[count($titleparts)-1];
                }
                
                if(preg_match("/[>&|.][\\\\._:(\\/=%].*/", $line)===1) {
                    $linebreaktype = mb_substr($line, 0, 1);
                    $linetype = mb_substr($line, 1, 1);
                    $content = mb_substr($line, 2, mb_strlen($line)-2);
                    if($linetype=="(") { //remove () from parenthetical
                        $content = mb_substr($content, 1, mb_strlen($content)-2);
                    }
                    if($nextLineNewElement) {
                        $script.=static::getOpeningTag($linetype);
                        $nextLineNewElement = false;
                    }
                    
                    $script.=$content.static::getLineSeperator($linebreaktype);
                    if($linebreaktype==".") $nextLineNewElement = true;
                }
            }
            Screenplay::create($title, $teamid, $script);
            return true;
        } else {
            // error opening the file.
        } 
        fclose($file);
    }
    
    public static function getOpeningTag($linetype) {
        switch($linetype) {
            case("\\"): return '<p class="scene">';
            case("."):  return '<p class="action">';
            case("_"):  return '<p class="character">';
            case(":"):  return '<p class="dialogue">';
            case("("):  return '<p class="parenthetical">';
            case("/"):  return '<p class="transition">';
            case("="):  return '<p class="shot">';
            case("%"):  return '<p class="note">';
            default:    return '<p class="action">';
        }
    }
    
    public static function getLineSeperator($linebreaktype) {
        switch($linebreaktype) {
            case(">"):  return ' ';
            case("&"):  return '';
            case("|"):  return ''; //dont know
            case("."):  return "</p>\n";
            default:    return '';
        }
    }
}
