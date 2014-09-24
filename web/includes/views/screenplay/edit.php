<?php
$this->registerJsFile('js/screenplay/tinymce_dev/tinymce.js');
$this->registerJsFile('js/screenplay/main.js', [
    'yii\web\JqueryAsset',
    'yii\bootstrap\BootstrapAsset',
    'app\assets\I18NAsset']);
$this->registerJsFile('js/screenplay/ScriptEditor.js', ['app\assets\I18NAsset']);
$this->registerJsFile('js/screenplay/Comment.js', ['app\assets\I18NAsset']);
$this->registerJsFile('js/screenplay/Layout.js', ['app\assets\I18NAsset']);
$this->registerJsFile('js/screenplay/TagTree.js', ['app\assets\I18NAsset']);
$this->registerJsFile('js/screenplay/CommonFunctions.js', ['app\assets\I18NAsset']);

$this->registerCssFile('css/screenplay.css');
$this->registerCssFile('css/icons.css');
//$this->registerCssFile('css/ui.fancytree.css');
?>

<script type="text/javascript">
    var isObserver = <?= $isObserver ? "true" : "false" ?>;
    var scriptId = <?= $screenplay->getId(); ?>;
</script>

<div class="page-container">
    <div class="left-container">

        <div id="tab_tagtree">
            <div class="container-toolbar">
                <div class="btn-group">
                    <button id="btn_add_category" href="#" class="btn btn-sm btn-default" tabindex="-1" accesskey="a" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', "Add category") ?>"  <?php if ($isObserver) echo 'disabled="true"' ?>><?= \Yii::t('app', 'Add') ?></button>
                    <button id="btn_delete_category" href="#" class="btn btn-sm btn-default" tabindex="-1" accesskey="d" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'Delete category') ?>" <?php if ($isObserver) echo 'disabled="true"' ?>><?= \Yii::t('app', 'Delete') ?></button>
                </div>
                <div class="btn-group">
                    <button id="btn_undo_tagTree" href="#" class="btn btn-sm btn-default" tabindex="-1" accesskey="z" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'undo') ?>" <?php if ($isObserver) echo 'disabled="true"' ?>><span class="icon icon-undo"></span></button>
                    <button id="btn_redo_tagTree" href="#" class="btn btn-sm btn-default" tabindex="-1" accesskey="y" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'redo') ?>" <?php if ($isObserver) echo 'disabled="true"' ?>><span class="icon icon-redo"></span></button> 
                </div>
                <button id="btnHelpModalTagTree" type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#help_modal_tagTree">?</button>

            </div>
            <div class="container-main">
                <div id="tagcategories" class="tagtree" tabindex="0" accesskey="c">
                    <ul>
                    </ul>
                </div>
            </div>
        </div>

        <div id="tab_scenelist">
            <div class="container-toolbar">

            </div>
            <div class="container-main">
                <div id="scenelist" class="tagtree" tabindex="0" accesskey="c">
                </div>
            </div>
        </div>
    </div>

    <div class="right-container">
        <div class="container-toolbar">
        
            <div class="editor-toolbar centered">     
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" data-placement="bottom" title="<?= \Yii::t('app', 'Hide/Show Tagtree or scene list') ?>" >
                        <span class="glyphicon glyphicon-th-large"></span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" style="right:inherit">
                        <li><a class="layoutHideLeft"><span class="glyphicon glyphicon-chevron-left"></span> <?= \Yii::t('app', 'Hide') ?></a></li>
                        <li><a class="layoutShowTagtree"><span class="glyphicon glyphicon-tree-deciduous"></span> <?= \Yii::t('app', 'Show Tagtree') ?></a></li>
                        <li><a class="layoutShowScenelist"><span class="glyphicon glyphicon-list"></span> <?= \Yii::t('app', 'Show scene list') ?></a></li>
                    </ul>
                </div>
                
                <select id="selectBlockType" data-width="auto" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'Formatter') ?>" <?php if ($isObserver) echo 'disabled="true"' ?>>
                    <option value="scene"><?= \Yii::t('app', 'Scene') ?></option>
                    <option value="action"><?= \Yii::t('app', 'Action') ?></option>
                    <option value="character"><?= \Yii::t('app', 'Character') ?></option>
                    <option value="dialogue"><?= \Yii::t('app', 'Dialogue') ?></option>
                    <option value="parenthetical"><?= \Yii::t('app', 'Parenthetical') ?></option>
                    <option value="transition"><?= \Yii::t('app', 'Transition') ?></option>
                    <option value="shot"><?= \Yii::t('app', 'Shot') ?></option>
                    <option value="note"><?= \Yii::t('app', 'Note') ?></option>
                </select>

                <div class="btn-group">
                    <button id="btnTag" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'Add/remove tag') ?>" <?php if ($isObserver) echo 'disabled="true"' ?>><span class="glyphicon glyphicon-tag"></span></button>
                    <button id="btnComment" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'create comment') ?>" ><span class="glyphicon glyphicon-comment"></span></button>
                </div>

                <div class="btn-group">
                    <button id="btn_bold" type="checkbox" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'bold') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-bold"></span></button>
                    <button id="btn_italic" type="checkbox" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'italic') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-italic"></span></button>
                    <button id="btn_underline" type="checkbox" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'underlined') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-underline"></span></button>
                </div>

                <div class="btn-group">
                    <button id="btnUndo" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'undo') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-undo"></span></button>
                    <button id="btnRedo" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'redo') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-redo"></span></button>
                </div>

                <div class="btn-group">
                    <button id="btnSpellChecking" type="checkbox" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'toggle spellchecking') ?> " <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-spell-check"></span></button>
                    <button id="btnForceBlockOrder" type="checkbox" class="btn btn-sm btn-default active" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'toggle filter') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="icon icon-filter"></span></button>
                </div>
                <div class="btn-group">
                    <div class="btn-group">
                        <button id="btnSave" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'save Screenplay') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?>><span class="glyphicon glyphicon-floppy-disk"></span></button>
                        <button type="button" data-toggle="dropdown" data-placement="bottom" title="<?= \Yii::t('app', 'Autosave settings') ?>" <?php if ($isObserver) { ?>disabled="true"<?php } ?> class="btn btn-sm btn-default dropdown-toggle">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#">
                                    <label>
                                        <input id="checkboxAutoSaveEnabled" type="checkbox" checked="checked"> <?= \Yii::t('app', 'Autosave enabled') ?>
                                    </label> 
                                    <span class="glyphicon glyphicon-time"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="btn-group">
                    <button id="btnHelpModalEditor" type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#help_modal_editor">?</button>
                </div>
            </div>

            <button id="btnToogleNavbar" type="button" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="<?= \Yii::t('app', 'Hide/Show navigationbar') ?>"><span class="glyphicon glyphicon-chevron-up"></span></button>
        </div>
        <div class="container-main">
            <div class="centered editor-and-comments-container">
                <div class="editor-container">
                    <textarea id="editor" name="content" class="editor">
                        <?= $screenplay->getLastRevision(); ?>
                    </textarea>
                </div>
                <div class="comments-container">
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>






<!------------------------------------------------------------------------------>
<!-------------------------- MODALS SECTION ------------------------------------>
<!------------------------------------------------------------------------------>

<div id="modals">

    <div class="modal fade" id="help_modal_tagTree" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="myModalLabel"><?= \Yii::t('app', 'Tagtree Help') ?></h2>
                </div>
                <div class="modal-body">
                    <h3>
                        <?= \Yii::t('app', 'How to use the tagtree') ?>:<br>
                    </h3>
                        
                        
                        <b>1.</b><?= \Yii::t('app', 'Click to create a new category. A popover will be shown in which you can type in the name of the category and select a color for the category. The category will be created as a child-category of the selected category.<br> Hotkey: <b>ALT + SHIFT + A </b>(Firefox)') ?><br>
                        <b>2.</b> <?= \Yii::t('app', 'Click to delete the selected Category. You are not able to delete the root category.<br> Hotkey: <b>ALT + SHIFT + D </b>(Firefox)') ?><br> 
                        <b>3.</b> <?= \Yii::t('app', 'doubleclick on a category to open the popover where you can modify the name and the color of the category.') ?><br>
                        <b>4.</b> <?= \Yii::t('app', 'Click and hold on a category to move it inside of the tree. Drop it where you want to put the category. It is not possible to place a category below an tagged object.') ?><br>
                        <b>5.</b> <?= \Yii::t('app', 'Click to undo your last change.<br> Hotkey: <b>ALT + SHIFT + Z </b>(Firefox)') ?><br>
                        <b>6.</b> <?= \Yii::t('app', 'Click to redo your last change.<br> Hotkey: <b>ALT + SHIFT + Y </b>(Firefox)') ?><br>
                        <br>
                        <?= \Yii::t('app', 'If you use another browser click ') ?>
                                <a href="http://en.wikipedia.org/wiki/Access_key"> here</a>
                        <?= \Yii::t('app', 'to see what are the  Hotkeys.') ?><br>
                        <?= \Yii::t('app', 'Attention: Hotkeys in chrome are only working when tagtree is focused!') ?><br /><br />
                    
                     <img src="media/help2.png">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close Help') ?></button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="help_modal_editor" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="myModalLabel"><?= \Yii::t('app', 'Editor Help') ?></h2>
                </div>
                <div class="modal-body">
                    <h3>
                        <?= \Yii::t('app', 'How to use the editor') ?>:<br>
                    </h3>

                    <?= \Yii::t('app', 'The text of the screenplay is splittet in different blocks. To create a new block you have
                    to click the <b>RETURN-KEY</b>.<br>
                    If you want to make a word wrap within a block you have to click
                    <b>Shift+Return</b>.<br>
                    To change the format of a block you have to click inside the block and click 
                    <b>TAB-KEY</b> or change the format by clicking the 1.Button and then choose the new format.') ?><br>
                    <h3>
                        <?= \Yii::t('app', 'Buttons explained') ?><br>
                    </h3>
                    <b>1.a)</b> <?= \Yii::t('app', 'Click to hide the Tagtree or the scene list.') ?><br>
                    <b>&nbsp;&nbsp;&nbsp;b)</b> <?= \Yii::t('app', 'Click to show the Tagtree.') ?><br>
                    <b>&nbsp;&nbsp;&nbsp;c)</b> <?= \Yii::t('app', 'Click to show the scene list.') ?><br>
                    <b>2.</b> <?= \Yii::t('app', 'Here you can select the formatting of the currently selected block. Toggle with <b>TAB</b>.') ?><br>
                    <b>3.</b> <?= \Yii::t('app', 'Click to tag the selected text or to remove an existing tag.Hotkey: <b>ALT + T</b>.') ?><br>
                    <b>4.</b> <?= \Yii::t('app', 'Click to add a comment.') ?><br>
                    <b>5.</b> <?= \Yii::t('app', 'Click to write <b>bold</b>.Hotkey: <b>CTRL + B</b>.') ?><br>
                    <b>6.</b> <?= \Yii::t('app', 'Click to write <em>italic</em>.Hotkey: <b>CTRL + I</b>.') ?><br>
                    <b>7.</b> <?= \Yii::t('app', 'Click to write <span style="text-decoration: underline">underlined</span>.Hotkey: <b>CTRL + U</b>.') ?><br>
                    <b>8.</b> <?= \Yii::t('app', 'Click to undo your last change.Hotkey: <b>CTRL + Z</b>.') ?><br>
                    <b>9.</b> <?= \Yii::t('app', 'Click to redo your last change.Hotkey: <b>CTRL + Y</b>.') ?><br>
                    <b>10.</b> <?= \Yii::t('app', 'Click to toggle the spellchecker.') ?><br>
                    <b>11.</b> <?= \Yii::t('app', 'Click to toggle the filter. The filter will disable blocks that are not usual with respect to the previous block. For example a <em>character</em> block should be followed by a <em>dialog</em>, so the filter will disable other blocks.') ?><br>
                    <b>12.</b> <?= \Yii::t('app', 'Click to save your text online.') ?><br>
                    <b>13.</b> <?= \Yii::t('app', 'Click to define and enable/disable autosave.') ?><br>
                    
                    <img src="media/help1.png" style="width:97%">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= \Yii::t('app', 'Close Help') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>







<!------------------------------------------------------------------------------>
<!-------------------------- TEMPLATES SECTION --------------------------------->
<!------------------------------------------------------------------------------>
<div id="templates" class="hidden">
    <div class="edit-tag-category" <?php if ($isObserver) { ?>disabled="true"<?php } ?>>
        <div class="form-horizontal" style="width:250px;height:230px; ">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label"><?= \Yii::t('app', 'Name') ?></label>
                <div class="col-sm-10">
                    <input name="tagName" type="text" class="form-control" id="tagCategoryNameInput" placeholder="Category Name" onEnter="this.select();">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label"><?= \Yii::t('app', 'Color') ?></label>
                <div class="col-sm-10">
                    <select name="colorpicker">
                        <option value="#d06b64">#d06b64</option>
                        <option value="#f83a22">#f83a22</option>
                        <option value="#fa573c">#fa573c</option>
                        <option value="#ff7537">#ff7537</option>
                        <option value="#ffad46">#ffad46</option>
                        <option value="#42d692">#42d692</option>
                        <option value="#16a765">#16a765</option>
                        <option value="#7bd148">#7bd148</option>
                        <option value="#b3dc6c">#b3dc6c</option>
                        <option value="#fbe983">#fbe983</option>
                        <option value="#fad165">#fad165</option>
                        <option value="#92e1c0">#92e1c0</option>
                        <option value="#9fe1e7">#9fe1e7</option>
                        <option value="#9fc6e7">#9fc6e7</option>
                        <option value="#4986e7">#4986e7</option>
                        <option value="#9a9cff">#9a9cff</option>
                        <option value="#b99aff">#b99aff</option>
                        <option value="#c2c2c2">#c2c2c2</option>
                        <option value="#cabdbf">#cabdbf</option>
                        <option value="#cca6ac">#cca6ac</option>
                        <option value="#f691b2">#f691b2</option>
                        <option value="#cd74e6">#cd74e6</option>
                        <option value="#a47ae2">#a47ae2</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button name="abort" type="button" class="btn btn-default"><?= \Yii::t('app', 'Abort') ?></button>
                    <button name="save" type="button" class="btn btn-default"><?= \Yii::t('app', 'Save changes') ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="scene-list-row list-group">
        <a href="#" class="list-group-item">
            <h5 class="scene-list-title list-group-item-heading"></h5>
        </a>
    </div>

</div>
