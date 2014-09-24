<?php

namespace app\models;
use app\models\Screenplay;
require_once \Yii::getAlias("@vendor") . '/PhpWord/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

class ExportPhpWord
{

    /**
     * remove all tags
     *
     */
    public static function strip_only_tags($in) {
        $regex  = '/<\/?[a-zA-Z0-9=\s\"\._]+>/';
        return preg_replace($regex,'',$in);
    }


    public static function exportPhpWord($name,$htmlin,$filename,$typ) {
        $html = "<body>".$htmlin."</body>";
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Verdana');
        $phpWord->setDefaultFontSize(12);

        $phpWord->addFontStyle('scene',
        array('bgColor'=>'cccccc'));
        $phpWord->addParagraphStyle('scenep',
        array('align' => 'left', 'spaceAfter'=>100));

        $phpWord->addFontStyle('action',
        array());
        $phpWord->addParagraphStyle('actionp',
        array('align' => 'left'));

        $phpWord->addFontStyle('character',
        array());
        $phpWord->addParagraphStyle('characterp',
        array('align' => 'center', 'spaceAfter'=>100));

        $phpWord->addFontStyle('dialogue',
        array());
        $phpWord->addParagraphStyle('dialoguep',
        array('align' => 'left', 'indent' => 3));

        $phpWord->addFontStyle('parenthetical',
        array());
        $phpWord->addParagraphStyle('parentheticalp',
        array('align' => 'left', 'indent' => 4));

        $phpWord->addFontStyle('transition',
        array());
        $phpWord->addParagraphStyle('transitionp',
        array('align' => 'right'));

        $phpWord->addFontStyle('shot',
        array());
        $phpWord->addParagraphStyle('shotp',
        array('align' => 'left', 'spaceAfter'=>100));

        $phpWord->addFontStyle('text',
        array());
        $phpWord->addParagraphStyle('textp',
        array('align' => 'left'));


        $properties = $phpWord->getDocumentProperties();
        $properties->setCreator('My name');
        $properties->setCompany('My factory');
        $properties->setTitle('My title');
        $properties->setDescription('My description');
        $properties->setCategory('My category');
        $properties->setLastModifiedBy('My name');
        $properties->setCreated(mktime(0, 0, 0, 3, 12, 2014));
        $properties->setModified(mktime(0, 0, 0, 3, 14, 2014));
        $properties->setSubject('My subject');
        $properties->setKeywords('my, key, word');


        $section = $phpWord->createSection();
        $xml = Screenplay::getSimpleXMLElementFromHtml($html);

        foreach ($xml->p as $p) {
            $class = ($p['class']==NULL) ? "" : ((string) $p['class']);
            $text = ($p==NULL) ? "" : (string) $p->asXML();
            if($class==NULL) continue;
            if($text==NULL) continue;
            if($class=="") continue;
            if($text=="") continue;
            if($class=="scene" || $class=="character" || $class=="transition" || $class=="shot") $text=strtoupper($text);
            if($class=="parenthetical") $text = '('.$text.')';
            $text = static::strip_only_tags(Screenplay::getTextFromXML($text));
            
            if($typ=="ODText") {
                $text = str_replace("&","&amp;",$text);
                $text = str_replace("<","&lt;",$text);
                $text = str_replace(">","&gt;",$text);
            }
            
            $section->addText($text,$class,$class."p");
        }

        $h2d_file_uri = tempnam('', 'htd');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $typ);
        $objWriter->save($h2d_file_uri);

        $filetyp = "application/octet-stream";
        switch($typ) {
            case("Word2007"): $filetyp = "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case("RTF"): $filetyp = "application/rtf"; break;
            case("ODText"): $filetyp = "application/vnd.oasis.opendocument.text"; break;
            default:$filetyp = "application/octet-stream"; break;
        }

        // Download the file:
        header('Content-Description: File Transfer');
        header('Content-Type: '.$filetyp);
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($h2d_file_uri));
        ob_clean();
        flush();
        $status = readfile($h2d_file_uri);
        unlink($h2d_file_uri);
        exit;
    }
}