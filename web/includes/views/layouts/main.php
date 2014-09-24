<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\I18NAsset;
use app\models\Setting;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
I18NAsset::register($this);

$this->registerJsFile('js/showPageInModal.js',['app\assets\I18NAsset']);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode(Setting::findSetting("pagetitle")->getValue()) ?></title>
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
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink'=>['label' => \Yii::t('app', 'Home'), 'url' => 'index.php'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
