<?php

use yii\helpers\Html;

$this->registerCssFile('css/showbreakdownreport.css');

$this->title = \Yii::t('app', 'Screenplay Breakdownreport').": ".Html::encode($screenplay->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Teams'), 'url' => ['team/index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($screenplay->getTeam()->name), 'url' => ['team/view','id'=>$screenplay->getTeam()->id]];
$this->params['breadcrumbs'][] = $this->title;

echo '<div id="breakdowncontent">';

echo "<center><h1>".Html::encode($screenplay->name)."</h1></center>";

if($breakdowndata==null || !is_array($breakdowndata) || count($breakdowndata)==0 || $breakdowndata["data"]==null || !is_array($breakdowndata["data"]) || count($breakdowndata["data"])==0) {
    \Yii::t('app', 'no results');
}

if($breakdowndata["type"]==1) {
    echo "<center><h4>-".\Yii::t('app', 'Breakdown by Scenes')."-</h4></center>";
    
    if($breakdowndata["input"]["gr"]) {
        echo makesvg($breakdowndata["data"],true);
    } else {
        echo '<button onclick="window.print();">'. \Yii::t('app', 'Print') .'</button>';
        foreach($breakdowndata["data"] as $scene) {
            echo "<h3>".Html::encode($scene["name"])."</h3><ul>";
            
            foreach($scene["tags"] as $cat => $tag) {
                echo "<li>(".Html::encode($cat).") ".Html::encode($tag)."</li>";
            }
            echo "</ul>";
        }
    }
} else if($breakdowndata["type"]==2) {
    $sceneNames = $screenplay->getScenes();
    echo "<center><h4>-".\Yii::t('app', 'Breakdown by Tags')."-</h4></center>";
    if($breakdowndata["input"]["gr"]) {
        echo makesvg($breakdowndata["data"],false);
    } else {
        echo '<button onclick="window.print();">'. \Yii::t('app', 'Print') .'</button>';
        foreach($breakdowndata["data"] as $tag) {
            echo "<h3>".HTML::encode($tag["title"])."</h3><ul>";
            
            foreach($tag["scenes"] as $scene) {
                echo "<li>".($scene+1).": ".Html::encode($sceneNames[$scene])."</li>";
            }
            echo "</ul>";
        }
    }
} else if($breakdowndata["type"]==4) {
    echo "<center><h4>-".\Yii::t('app', 'Statistics')."-</h4></center>";
    echo '<button onclick="window.print();">'. \Yii::t('app', 'Print') .'</button>';
    echo '<div class="list-group">';
    
    echo '<div class="list-group-item"><h4>'.\Yii::t('app', 'total statistics').'</h4>';
    
    echo $breakdowndata["data"]["words"]." ".\Yii::t('app', 'words')."<br />";
    if($breakdowndata["data"]["wordcount"]!=null && is_array($breakdowndata["data"]["wordcount"]) && count($breakdowndata["data"]["wordcount"])>0) {
        foreach($breakdowndata["data"]["wordcount"] as $k => $v) {
            if($k=="dialogue") continue;
            if($k=="character") $name=\Yii::t('app', "dialoge line"); else $name=$k;
            echo $breakdowndata["data"]["pcount"][$k]." ".$name.($breakdowndata["data"]["pcount"][$k]!=1 ? "s" : "")."<br />";
        }
    }
    echo '</div>';
    echo '<div class="list-group-item"><h4>'.\Yii::t('app', 'statistics for selected categories').'</h4>';
    echo \Yii::t('app', 'Tags (without child categories)').": ".$breakdowndata["data"]["characters"]."<br /><br />";
    

    echo '</div></div>';
} else {
    echo "error";
}


echo "</div>";






function makegrid($charactersno,$scenesno,$width,$height) {
    $space=20;
    $tmp='<line x1="0" y1="'.$space.'" x2="'.$width.'" y2="'.$space.'" stroke="grey" stroke-width="1"/>'."\n";
    $x=40;
    for($i=0;$i<$charactersno;$i++) {
        $tmp.='<line x1="0" y1="'.$x.'" x2="'.$width.'" y2="'.$x.'" stroke="grey" stroke-width="1"/>'."\n";
        $x+=$space;
    }
    
    $space=100;
    $tmp.='<line x1="140" y1="0" x2="140" y2="'.$height.'" stroke="grey" stroke-width="1"/>'."\n";
    $y=240;

    for($i=0;$i<$scenesno;$i++) {
        $tmp.='<line x1="'.$y.'" y1="0" x2="'.$y.'" y2="'.$height.'" stroke="grey" stroke-width="1"/>'."\n";
        $y+=$space;
    }
    
    return $tmp;
}

function makescenes($scenes,$isScenes) {
    $space=100;
    $tmp="";
    $x=145;
    $i=1;
    
    foreach($scenes as $scene) {
        if($isScenes) $tmp.='<text x="'.$x.'" y="13" font-family="Verdana" font-size="10">'.$i.' '.Html::encode(substr($scene["name"],0,12)).'</text>'."\n";
        else $tmp.='<text x="'.$x.'" y="13" font-family="Verdana" font-size="10">'.$i.' '.Html::encode(substr($scene,0,12)).'</text>'."\n";
        $x+=$space;
        $i++;
    }

    return $tmp;
}

function makechars($characters,$isScenes) {
    $space=20;
    $tmp="";
    $y=33;
    
    foreach($characters as $character) {
        if($isScenes) $tmp.='<text x="5" y="'.$y.'" font-family="Verdana" font-size="10">'.Html::encode(substr($character,0,22)).'</text>'."\n";
        else $tmp.='<text x="5" y="'.$y.'" font-family="Verdana" font-size="10">'.Html::encode(substr($character["name"],0,22)).'</text>'."\n";
        $y+=$space;
    }

    return $tmp;
}

function makedots($data) {
    $xspace=100;
    $x=140;
    $yspace=20;
    $y=20;
    $tmp="";
    
    $colors=["red","blue","green","yellow","black","brown","pink"];
    
    $i=0;
    foreach($data as $tag) {
        $color = $colors[$i % 7];
        foreach($tag as $nr) {
            $tmp.='<rect x="'.($x+($xspace*$nr)).'" y="'.$y.'" width="100" height="20" fill="'.$color.'" />'."\n";
        }
        $y+=$yspace;
        $i++;
    }
    return $tmp;
}

function makesvg($data,$isScenes) {
    $SceneToTags = [];
    $TagToScenes = [];
    $TagToNr = [];
    $NrToTag = [];
    $i = 0;
    foreach($data as $scenenr => $scene) {
        $SceneToTags[$scenenr] = [];
        
        foreach($scene["tags"] as $key => $tag) {
            $elements = explode("/",$key,2);
            if(count($elements)<2) continue;
            $tagname = $elements[1];
            if($tagname=="") continue;
            
            if(!array_key_exists($tagname,$TagToScenes)) $TagToScenes[$tagname] = [];
            if(!in_array($scenenr,$TagToScenes[$tagname])) $TagToScenes[$tagname][] = $scenenr;
            
            if(!in_array($tagname,$NrToTag)) {
                $NrToTag[$i] = $tagname;
                $TagToNr[$tagname] = $i;
                $i++;
            }

            if(!in_array($TagToNr[$tagname], $SceneToTags[$scenenr])) $SceneToTags[$scenenr][] = $TagToNr[$tagname];
        }
    }

    if($isScenes) {
        $width = 141 + 100*count($data);
        $height= 21 + 20*count($TagToNr);
        
        $svg="";
        $svg.='<svg width="'.$width.'" height="'.$height.'" version="1.1" xmlns="http://www.w3.org/2000/svg">';
        $svg.=makegrid(count($TagToNr),count($data), $width, $height);
        $svg.=makescenes($data,true);
        $svg.=makechars($NrToTag,true);
        $svg.=makedots($TagToScenes);
        $svg.='</svg>';
    } else {
        $width = 141 + 100*count($TagToNr);
        $height= 21 + 20*count($data);
        
        $svg="";
        $svg.='<svg width="'.$width.'" height="'.$height.'" version="1.1" xmlns="http://www.w3.org/2000/svg">';
        $svg.=makegrid(count($data),count($TagToNr), $width, $height);
        $svg.=makescenes($NrToTag,false);
        $svg.=makechars($data,false);
        $svg.=makedots($SceneToTags);
        $svg.='</svg>';
    }
    
    return $svg;
}

?>