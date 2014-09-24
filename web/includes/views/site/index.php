<?php
use \app\models\Setting;
/**
 * @var yii\web\View $this
 */
$this->title = 'Screenwriting authoring web platform';
?>
<div class="site-index">

   <?php echo Setting::findSetting("frontPage")->getValue(); ?>
</div>
