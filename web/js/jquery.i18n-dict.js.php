<?php
header("content-type: application/javascript");

$lang = "";
$langpath = __DIR__."/../../includes/config/language.php";
if(file_exists($langpath)) $lang = require($langpath);

$path = __DIR__."/../../includes/messages/".$lang."/js.php";
$array = [];
if(file_exists($path)) $array = require($path);

echo "var my_dictionary = {\n";

foreach($array as $k => $v) {
    echo '"'.$k.'" : "'.$v.'",'."\n";
}

echo "}\n";
echo "$.i18n.load(my_dictionary);\n";

?>
