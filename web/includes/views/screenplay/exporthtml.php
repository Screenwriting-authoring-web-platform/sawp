<html>
<head>
<meta charset="UTF-8"/>
<title><?php echo $name; ?></title>
<style type="text/css">
body {
    text-align: center;
}
#wrapper {
    text-align: left;
}
<?php if($box) { ?>@media screen {
#wrapper {
    padding: 10px;
    width: 600px;
    margin: 0 auto;
    border: 1px solid #888;
    box-shadow: 0px 0px 10px #888;
}
}<?php } 

echo file_get_contents(\Yii::getAlias('@webroot')."/css/scriptFormats.css");

echo $tagcss; ?>

</style>
</head>
<body>

<script type="text/php">
if ( isset($pdf) ) {
  $font = Font_Metrics::get_font("arial", "bold");
  $pdf->page_text(20, 20, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 8, array(0,0,0));
}
</script>

<h1><?php echo $name; ?></h1>
<div id="wrapper">

<?php echo $html; ?>

</div>
</body>
</html>