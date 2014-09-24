<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//$this->registerJsFile('js/export.js');

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\AddteamForm $model
 */
 
$this->title = Yii::t('app', 'Export');
$form = ActiveForm::begin([
    'id' => 'export-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ]
]); 

    $ra = array();
    $ra[1] = \Yii::t('app', 'HTML');
    $ra[2] = \Yii::t('app', 'PDF');
    $ra[3] = \Yii::t('app', 'DOCX');
    $ra[4] = \Yii::t('app', 'ODT');
    $ra[5] = \Yii::t('app', 'RTF');

?>
   
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
</div>
<div class="modal-body">

    <?= Html::activeHiddenInput($model, 'screenplayid', ["value" => $screenplay->getId()]) ?>
    <?= $form->field($model, 'format')->dropDownList($ra)->label(\Yii::t('app', 'Format')); ?>
    <?= $form->field($model, 'notes')->checkbox([], false)->label(Yii::t('app', 'Notes')); ?>
    <?= $form->field($model, 'tags')->checkbox([], false)->label(Yii::t('app', 'Tags')); ?>
    
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <?= Html::submitButton('export', ['class' => 'btn btn-primary', 'name' => 'export-button']) ?>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $("#exportform-format").val(1);
    $("#exportform-format").change(function() {
        var type = $(this).val();
        var foo = ".field-exportform-tags";

        $(foo).each(function() {
            $(this).hide();
        });
        
        function show(selector) {
            $(selector).each(function() {
                $(this).show();
            });
        }

        switch(type) {
            case("1"): show(foo); break;
            case("2"): show(foo); break;
            default: break;
        }
    });
</script>