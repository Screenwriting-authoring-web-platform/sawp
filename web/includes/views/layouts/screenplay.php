<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\assets\Select2;
use app\assets\JqueryUIAsset;
use app\assets\FancytreeAsset;
use app\assets\ColorPickerAsset;
use app\assets\I18NAsset;
use app\models\Setting;
use app\assets\JqueryScrollToAsset;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
Select2::register($this);
JqueryUIAsset::register($this);
JqueryScrollToAsset::register($this);
FancytreeAsset::register($this);
ColorPickerAsset::register($this);
I18NAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => html::encode(Setting::findSetting("pagetitle")->getValue()),
                'brandUrl' => html::encode(Setting::findSetting("pagehomeurl")->getValue()),
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            $widgetItems= [
                    ['label' => \Yii::t('app', 'Home'), 'url' => ['/site/index']],
                    ['label' => \Yii::t('app', 'Contact'), 'url' => ['/site/contact']]
                    ];
            if(Yii::$app->user->isGuest) {
                $widgetItems[]=['label' => \Yii::t('app', 'Login'), 'url' => ['/site/login']];
                $widgetItems[]=['label' => \Yii::t('app', 'Register'), 'url' => ['/site/register']];
            } else {
                $widgetItems[]=['label' => \Yii::t('app', 'Teams'), 'url' => ['/team/index']];
                if(\Yii::$app->user->identity->isAdmin()) {
                    $widgetItems[]=['label' => \Yii::t('app', 'Administration'), 'url' => ['/admin/index']];
                }
                $widgetItems[]=['label' => \Yii::t('app', 'Logout'),'url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']];
                $text = Html::encode(\Yii::$app->user->identity->username)." ".Yii::$app->user->identity->get_gravatar($s = 20, $d = 'identicon', $r = 'g', $img = true );
                $widgetItems[]=['label' => $text, 'url' => ['/user/view', 'id'=>Yii::$app->user->identity->id]];               
            }
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => $widgetItems,
            ]);
            
            NavBar::end();
            
            echo $content;
        ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
