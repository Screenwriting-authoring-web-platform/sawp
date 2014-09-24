var tinyMCEInstance = null;
var editorContainerDiv = null;
var editorAndCommentsContainerDiv = null;
var commentsContainerDiv = null;
var autoSaveIntervalInSeconds = 60;

/**
 * Reads all block types from the select-Component in HTML and returns them as array.
 * @returns {String[]} Array of string containing all possible block types identifiers
 */
function getAllBlockTypes() {
    var ret = [];
    $("#selectBlockType > option").each(function() {
        ret.push($(this).val());
    });
    return ret;
}

/**
 * Checks if the specified jQueryNode is a block and not a span, strong or any other tag.
 * @param {jQueryNode} jQueryNode
 * @returns {Boolean} True if the specified jQueryNode is a block, false otherwise.
 */
function isBlock(jQueryNode) {
    //assertSinglejQueryNode(jQueryNode);
    if (jQueryNode.length != 1)
        return false;
    isParagraph = jQueryNode.prop('tagName').toLowerCase() === 'p';
    if (!isParagraph)
        return false;

    var parent = jQueryNode.parent();
    if (parent.attr('id') !== 'tinymce')
        return false;
    if (parent.prop('tagName').toLowerCase() !== 'body')
        return false;
    return true;
}

/**
 * assert that the given node is a blockNode and prints an error to the console otherwise
 * @param {jQueryNode} jQueryNode
 * @returns {undefined}
 */
function assertIsBlockNode(jQueryNode) {
    if (!isBlock(jQueryNode))
        console.error(jst("js", 'Expected a block node'));
}
/**
 * Returns the block node that the node belongs to.
 * @param {jQuery} jQueryNode 
 * @returns {jQuery} The block node that the input node belongs to. This could be the same node as the input node or a parent.
 */
function getBlock(jQueryNode) {
    if (isBlock(jQueryNode))
        return jQueryNode;

    var parentP = jQueryNode.parents("p:first");
    if (!isBlock(parentP))
        return null;
    return parentP;
}

/**
 * returns the type of the given node (scene, character, shot, etc.) 
 * @param {jQueryNode} jQueryNode
 * @returns {String} the type 
 */
function getBlockType(jQueryNode) {
    var blockNode = getBlock(jQueryNode);
    return blockNode.attr('class');
}

/**
 * sets the block type of a given node
 * @param {jQueryNode} jQueryNode The node
 * @param {type} blockType the blockType
 * @returns {undefined}
 */
function setBlockType(jQueryNode, blockType) {
    var blockNode = getBlock(jQueryNode);
    blockNode.attr("class", blockType);
}

/*
 * Checks if some text is selected.
 * @returns {Boolean} true if some text is selected (whitespaces are ignored), false otherwise
 */
function isTextSelected() {
    return !tinyMCEInstance.selection.isCollapsed();
}

/*
 * returns the selected text or the word at the cursor position if nothing is selected
 * @returns {String} The selected text
 */
function getSelectedText() {
    if (tinyMCEInstance.selection.isCollapsed())
        selectWordAtCursorPosition();
    return tinyMCEInstance.selection.getContent({format: 'text'});
}

/**
 * selects the word at the cursor position
 * @returns {undefined}
 */
function selectWordAtCursorPosition() {
    //the following code retrievs the selected word from the browsers selection object
    var sel = tinyMCEInstance.selection.getSel();
    sel.collapseToStart();
    sel.modify("move", "backward", "word");
    sel.modify("extend", "forward", "word");
    tinyMCEInstance.selection.select();
}

/*
 * In the editor text can be marked with a tag containing class. This method finds all
 * markers in the given nodes.
 * @param {jqueryNodeCollection} nodes the blocks in which to seek for markers
 * @returns {String[]} the array containing all markers in the selected block nodes
 */
function getAllMarkers(nodes) {
    
    if (nodes === null)
        return null;

    var markers = [];
    nodes.children().each(function() {
        var classAttr = $(this).attr('class');
        if (typeof (classAttr) !== "undefined") {
            var elementClasses = classAttr.split(/\s+/);
            for (i = 0; i < elementClasses.length; i++)
                markers.push(elementClasses[i]);
        }
    });
    return markers;
}

/*
 * Removes all markers in the selection.
 * @returns {undefined}
 */
function removeAllMarkersInSelection() {
    removeMarkersInSelectionStartingWith("");
}

/**
 * Removes all markes in the selection that start with the given prefix. Use this
 * methode for example to remove markes starting with "tagged_"
 * @param {String} markerNamePrefix The prefix of the markers to delete
 * @returns {undefined}
 */
function removeMarkersInSelectionStartingWith(markerNamePrefix) {
    var markers = getAllMarkers(getSelectedBlockNodes());
    if (markers == null)
        return;
    for (var i = 0; i < markers.length; i++) {
        if (markers[i].indexOf(markerNamePrefix) === 0)
            removeMarkerInSelection(markers[i]);
    }
}

/**
 * Removes all markers with the given name in the selection
 * @param {String} markerName the name of the marker to be removed
 * @returns {undefined}
 */
function removeMarkerInSelection(markerName) {
    //if(tinyMCEInstance.selection.isCollapsed())
    //   selectWordAtCursorPosition();   
    tinyMCEInstance.formatter.remove('applyClass', {class: markerName});
}

/**
 * Removes all markers with the given name in the complete script
 * @param {String} markerName the name of the marker to be removed
 * @returns {undefined}
 */
function removeMarkerInCompleteScript(markerName){
    getAllBlockNodes().find("."+markerName).contents().unwrap();
}

/*
 * removes all tags in the editor which belong to an category which has been deleted in the tagTree
 * @param {int[]} validCategoryIds an array containing the category IDs of all existing categories
 * @returns {undefined}
 */
function removeNoMoreExistingTagsFromEditor(validCategoryIds) {
    var markers = getAllMarkers(getAllBlockNodes());
    if (markers === null)
        return;
    
    for (var i = 0; i < markers.length; i++) {
        if(markers[i].length>8 && markers[i].substring(0,8)==="category"){
            var markerCategoryId=markers[i].substring(8);
            var isInvalid = ($.inArray(markerCategoryId,validCategoryIds) === -1);
            
            if(isInvalid)
                removeMarkerInCompleteScript(markers[i]);
        }  
    }
}

/**
 * Returns the position of the current selection in the editor in pixels relative to
 * the editor container div element.
 * @returns {[x,y,w,h]} Array containing the bounds of the selected text. the
 * coordinates are in pixels and relative to the editor container div element.
 */
function getSelectionBoundsRelativeToEditorContainerDiv() {
    $(tinyMCEInstance.getBody()).find(".positionMarker").contents().unwrap(); //clear old position markers

    markSelectedText("positionMarker");  //this tag is used only to get the position of the selected text
    var positionMarker = $(tinyMCEInstance.getBody()).find(".positionMarker");
    var selectionOffset = positionMarker.offset();
    var selectionWidth = positionMarker.width();
    var selectionHeight = positionMarker.height();
    removeMarkerInSelection('positionMarker');
    return {x: selectionOffset.left, y: selectionOffset.top, w: selectionWidth, h: selectionHeight};
}
/**
 * Returns the absolute position of the current selection in the editor in pixels
 * in the browser window.
 * @returns {[x,y,w,h]} Array containing the bounds of the selected text. the
 * coordinates are in pixels.
 */
function getAbsoluteSelectionBounds() {
    var relativeBounds = getSelectionBoundsRelativeToEditorContainerDiv();
    var editorOffset = editorContainerDiv.offset();
    return {x: relativeBounds.x + editorOffset.left, y: relativeBounds.y + editorOffset.top, w: relativeBounds.w, h: relativeBounds.h};
}

/**
 * creates a html div element below the selction in the editor. The div element will be added as child of the body with an absolute position.
 * @returns {jQueryNode} the div element
 */
function createAbsolutePositionedDivAtSelection() {
    var selectionBounds = getAbsoluteSelectionBounds();
    var absolutePositionedDiv = $('<div style="position:absolute;top:' + (selectionBounds.y + selectionBounds.h + 5) + 'px;left:' + (selectionBounds.x) + 'px"></div>');
    $('body').append(absolutePositionedDiv);
    return absolutePositionedDiv;
}

/*
 * updates the category colors in the editor. Has to be called if a category color changes
 * @param {Array [[id,color],...] } idsAndColorArray Array containing tag ids and matching color i.e: [{id:1,color:'#fff'},{id:2,color:'#f00'},...]
 * @returns {undefined}
 */
function updateCategoryColorsInEditor(idsAndColorArray) {
    var css = "<style id='categoryStyles'> \n<!--\n";
    for (i = 0; i < idsAndColorArray.length; i++) {
        css += '.category' + idsAndColorArray[i].id + '{background-color:' + idsAndColorArray[i].color + ';}\n';
    }
    css += "-->\n</style>";
    if (tinyMCEInstance == null)
        return;
    if (tinyMCEInstance.dom == null)
        return;
    if (tinyMCEInstance.dom.doc == null)
        return;
    var head = $(tinyMCEInstance.dom.doc).find('head');
    head.find('#categoryStyles').remove();
    head.append(css);
}

/**
 * Updates the scenes listed in the scene list view based on the editor content.
 * Call this whenever the user created a new scene or deleted a scene to update
 * the scene list view.
 * @returns {undefined} nothing
 */
function updateSceneList() {

    var templateHtml = $('#templates > .scene-list-row').html();
    $('#scenelist').empty();

    var blockNodes = getAllBlockNodes();
    var count = 1;

    for (var i = 0; i < blockNodes.length; i++) {
        var currentBlock = $(blockNodes.get(i));

        if (currentBlock.hasClass('scene')) {
            var sceneListRow = $(templateHtml);
            sceneListRow.find('.scene-list-title').text(count + ". " + currentBlock.text());
            $('#scenelist').append(sceneListRow);
            sceneListRow.data('node', currentBlock);

            sceneListRow.click(function() {
                var scrollTo = $(this).data('node').position().top;
                console.debug("scroll to:" + scrollTo + " i:" + i);
                $(".right-container .container-main").scrollTo(scrollTo, 800);
            });

            count++;
        }
    }
}


/**
 * marks the selected text with the given marker name
 * @param {String} markerName The name of the marker
 * @returns {undefined}
 */
function markSelectedText(markerName) {
    removeAllMarkersInSelection();
    tinyMCEInstance.formatter.apply('applyClass', {class: markerName});
}

/*
 * returns all block nodes
 * @returns {jQueryNodes} an jQueryNode containing all block nodes
 */
function getAllBlockNodes() {
    return $(tinyMCEInstance.getDoc()).find('body').children();
}

/**
 * returns the first block node of the selected text
 * @returns {jQueryNode} the first block node
 */
function getFirstSelectedBlockNode() {
    var nodes = getSelectedBlockNodes();
    if (nodes == null)
        return null;
    return nodes.first();
}

/**
 * returns all block nodes of the selected text
 * @returns {jQueryNodes} all selected block nodes
 */
function getSelectedBlockNodes() {
    var startBlock = getBlock($(tinyMCEInstance.selection.getStart()));
    var endBlock = getBlock($(tinyMCEInstance.selection.getEnd()));
    if (startBlock == null)
        return null;
    if (endBlock == null)
        return null;

    var afterEnd = endBlock.next();
    if (afterEnd.length === 0)
        return startBlock.nextAll().addBack();
    else
        return startBlock.nextUntil(afterEnd).addBack();
}

/*
 * Switches the type of the given block node to the next possible type
 * @param {jQueryNode} blockNode The node which type should change
 * @returns {undefined}
 */
function togglePossibleBlockType(blockNode) {
    var currentType = getBlockType(blockNode);
    var possibleTypes = getAllowedBlockTypesForNode(blockNode);

    var index = $.inArray(currentType, possibleTypes);
    var newIndex = (index + 1) % possibleTypes.length;

    setBlockType(blockNode, possibleTypes[newIndex]);
    setBlockTypeSelectToNodeType(blockNode);
}

/**
 * updates the state of the given toolbar button ( bold, italic and underline )
 * @param {String} styleIdentfier bold, italic or underline
 * @returns {undefined}
 */
function updateStyleButton(styleIdentfier) {
    hasStyle = tinyMCEInstance.formatter.match(styleIdentfier);
    if (hasStyle)
        $('#btn_' + styleIdentfier).addClass('active');
    else
        $('#btn_' + styleIdentfier).removeClass('active');
}


/**
 * Updates the select and bold, italic and underline buttons in the toolbar based
 * on the currently selected block.
 * @returns {undefined}
 */
function editorHandleSelectedNodeChanged() {
    var currentNode = getFirstSelectedBlockNode();


    updateStyleButton('bold');
    updateStyleButton('italic');
    updateStyleButton('underline');

    if (currentNode != null)
        setBlockTypeSelectToNodeType(currentNode);
    updateSceneList();
}

/**
 * Returns the possible block types based on the type of the previous block.
 * @param {String} previousBlockType the type of the previous block
 * @returns {String[]} array containing all allowed block type names
 */
function getAllowedBlockTypes(previousBlockType) {
    switch (previousBlockType) {
        case 'scriptstart':
            return ['scene', 'transition', 'note'];
        case 'scene':
            return ['shot', 'transition', 'action', 'character', 'note'];
        case 'shot':
            return ['action', 'character', 'transition', 'scene', 'note'];
        case 'character':
            return ['dialogue', 'parenthetical', 'note'];
        case 'dialogue':
            return ['character', 'parenthetical', 'action', 'shot', 'scene', 'note'];
        case 'transition':
            return ['shot', 'character', 'scene', 'note'];
        case 'action':
            return ['character', 'dialogue', 'scene', 'note'];
        default:
            return getAllBlockTypes();
    }
}

/**
 * Returns the possibly block types for the given node based on the previous nodes.
 * @param {jQueryNode} blockNode the node for which the allowed block types should be returend
 * @returns {String[]} array containg allowed block types for the given node
 */
function getAllowedBlockTypesForNode(blockNode) {
    var isFilterBlockTypesOn = $('#btnForceBlockOrder').hasClass('active');
    if (!isFilterBlockTypesOn)
        return getAllBlockTypes();

    var previousNode = blockNode.prev();
    var previousNodeType = 'scriptstart';
    if (previousNode.length === 1)
        previousNodeType = getBlockType(previousNode);

    if (previousNodeType === 'note')    //ignore note blocks
        return getAllowedBlockTypesForNode(previousNode);

    return getAllowedBlockTypes(previousNodeType);
}

/**
 * Sets the type for the new block and updates the toolbar
 * @param {tinymce event args} newBlockArgs tinymce newBlock event arguments
 * @returns {undefined}
 */
function editorHandleNewBlockCreated(newBlockArgs) {
    console.debug('new block');
    var newBlock = $(newBlockArgs.newBlock);

    var possibleNextTypes = getAllowedBlockTypesForNode(newBlock);
    setBlockType(newBlock, possibleNextTypes[0]);

    setBlockTypeSelectToNodeType(newBlock);
}

/**
 * Sets the select for the block types in the toolbar to the type of the given node. Also all
 * options in the select that are not allowed for the given block are disabled.
 * @param {jQueryNode} blockNode The node which type should be set to the select
 * @returns {undefined}
 */
function setBlockTypeSelectToNodeType(blockNode) {
    currentBlockType = getBlockType(blockNode);
    $('#selectBlockType').select2('val', currentBlockType);

    var possibleTypes = getAllowedBlockTypesForNode(blockNode);

    $('#selectBlockType > option').each(function(index) {
        var self = $(this);

        if ($.inArray(self.val(), possibleTypes) === -1)
            self.attr("disabled", 'true');
        else
            self.removeAttr('disabled');
    });
}

/**
 * Toogles the given style of the selected editor text on or of depending on the current
 * state. The buttons in the toolbar are also updated
 * @param {String} style must be one of the following: bold,italic,underline
 * @returns {undefined} nothing
 */
function toggleStyleOfSelection(style) {
    $('#btn_' + style).toggleClass('active');
    if ($('#btn_' + style).hasClass('active'))
        tinyMCEInstance.formatter.apply(style);
    else
        tinyMCEInstance.formatter.remove(style);
    tinyMCEInstance.focus();
}


function editorHandleKeyDown(event) {
    //console.debug('Key down event: ' + event.keyCode);
    if (!isObserver) {
        switch (event.keyCode) {
            case 9: //KEY_TAB
                tinymce.dom.Event.cancel(event); //prevent focus changed by tab
                togglePossibleBlockType(getFirstSelectedBlockNode());
                break;
            case 8: //KEY_BACKSPACE
                break;
            case 84:  //KEY_T
                if (event.altKey) {
                    layoutShowTagtree();
                    showCategorySelect();
                }
                break;
            case 32: //KEY_SPACE
                removeAllMarkersInSelection();
                break;
            case 13: //KEY_ENTER
                if(event.shiftKey){
                    /* fix: add a whitespace when breaking inside a block
                     * otherwise tagging will tag above the linebreak because
                     * tinymce selection ignores br tags*/
                    tinyMCEInstance.insertContent(" ");
                }
                removeAllMarkersInSelection();
                break;
        }/*
         if(event.keyCode === 67)
         showCreateComment();*/
    }
}

/**
 * Initalize select2 at the given Node. See http://ivaynberg.github.io/select2/ for further information.
 * @param {jQueryNode} selectBox The node where the select should be opened.
 * @returns {undefined}
 */
function initCategorySelectBox(selectBox) {
    selectBox.select2({
        data: getCategoryNamesAndIdsAsArray(),
        allowClear: true,
        dropdownAutoWidth: true,
        width: '200px',
        multiple: true,
        maximumSelectionSize: 1,
        formatNoMatches: function() {
            return jst("js", '<p>No matches found.</p><p><b>Create the category first!<b></p>');
        }
    });
}

/**
 * Creates the tag in the editor and/or a new category in the tree.
 * @param {select2} selectBox the select2 box which calls this function
 * @returns {undefined}
 */
function handleCategorySelectBoxChange(selectBox) {
    var tagId = selectBox.select2('val')[0];
    if (nodeIsCategory(tagId)) {
        var tagName = getSelectedText();
        tagId = createNewTagInCategory(tagId, tagName);
    }
    markSelectedText('category' + tagId);
}

/**
 * Closes the select2 box and sets the focus to the editor.
 * @param {select2} selectBox the select2 box which calls this function
 * @returns {undefined}
 */
function handleCategorySelectBoxClose(selectBox) {
    setTimeout(function() {  //wait for change event to be fired
        selectBox.select2('destroy');
        selectBox.remove();
        tinyMCEInstance.focus();
    }, 100);
}

/**
 * Opens the category select below the selected editor text and installs the input handlers
 * @returns {undefined}
 */
function showCategorySelect() {
    var selectBox = createAbsolutePositionedDivAtSelection();
    initCategorySelectBox(selectBox);
    selectBox.on('change', function() {
        handleCategorySelectBoxChange(selectBox)
    });
    selectBox.on('select2-close', function() {
        handleCategorySelectBoxClose(selectBox)
    });
    selectBox.select2('open');

}

function performAutosaveIfEnabled() {
    if ($('#checkboxAutoSaveEnabled').prop('checked')) {
        if(!isObserver) {
            saveEditorContent();
        }
    }
}

function saveEditorContent() {
    var saveButtonElement = $('#btnSave');
    var action = "?r=ajax/savescreenplaycontent&id=" + scriptId;
    var content = tinyMCEInstance.getContent();
    saveButtonElement.html('<img style="width: 10px;" src="media/spinner.gif">');
    saveButtonElement.prop('disabled', true);

    $.post(action, {'content': content}, null, 'json')
            .fail(function() {
                alert(jst("js", 'Error while saving'));
            })
            .done(function(json) {
                if (json.status !== 'ok' && json.status !== 'unchanged')
                    alert(jst("js","Could not save script: A server side error occured. Please contact an admin.\nStatus:") + json.status);
            })
            .always(function() {
                setTimeout(function() {
                    saveButtonElement.html('<span class="glyphicon glyphicon-floppy-disk">');
                    saveButtonElement.prop('disabled', false);
                }, 500);
            });
}

function editorHandleLoadContent() {
    bindCommentAnchors();
    updateCategoryColorsInEditor(getCategoryIdsAndColors());
}

function initializeScriptEditor() {
    tinyMCEInstance = new tinymce.Editor('editor', {
        menubar: false,
        statusbar: false,
        toolbar: false,
        plugins: "autoresize,paste",
        
        /*configure paste plugin to remove style tags and invalid classes.*/
        paste_auto_cleanup_on_paste: true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: true,
        
        autoresize_min_height: 774,
        content_css: "css/scriptFormats.css",
        forced_root_block: 'p',
        readonly: isObserver ? 1 : 0,
        formats: {
            applyClass: {inline: 'span', attributes: {class: '%class'}}
        },
        setup: function(editor) {
            editor.on('keydown', editorHandleKeyDown);
            editor.on('nodechange', editorHandleSelectedNodeChanged);
            editor.on('newblock', editorHandleNewBlockCreated);
            //editor.on('init', editorHandleInit);
            editor.on('loadcontent', editorHandleLoadContent);

        }
    }, tinymce.EditorManager);
    tinyMCEInstance.render();
    editorContainerDiv = $('.editor-container');
    commentsContainerDiv = $('.comments-container');
    editorAndCommentsContainerDiv = $('.editor-and-comments-container');

    $('#btnTag').click(function() {
        layoutShowTagtree();
        showCategorySelect();
    });
    $('#btn_bold').click(function() {
        toggleStyleOfSelection('bold');
    });
    $('#btn_italic').click(function() {
        toggleStyleOfSelection('italic');
    });
    $('#btn_underline').click(function() {
        toggleStyleOfSelection('underline');
    });

    $('#btnUndo').click(function() {
        tinyMCEInstance.undoManager.undo();
    });
    $('#btnRedo').click(function() {
        tinyMCEInstance.undoManager.redo();
    });

    $('#selectBlockType').select2({
        allowClear: false,
        dropdownAutoWidth: true,
        width: 'element',
        minimumResultsForSearch: 20, //prevent filter input
        maximumSelectionSize: 20 // prevent scrollbar
    });

    $('#selectBlockType').change(function() {
        getSelectedBlockNodes().each(function(index) {
            var self = $(this);
            setBlockType(self, $('#selectBlockType').val());
        });
        tinyMCEInstance.focus();
    });

    $('#btnForceBlockOrder').click(function() {
        $('#btnForceBlockOrder').toggleClass('active');
        var selectedBlock = getFirstSelectedBlockNode();
        setBlockTypeSelectToNodeType(selectedBlock);
    });

    $('#btnSpellChecking').click(function() {
        $('#btnSpellChecking').toggleClass('active');
        var spellcheckValue = $('#btnSpellChecking').hasClass('active') ? 'true' : 'false';
        $(tinyMCEInstance.getBody()).attr("spellcheck", spellcheckValue);
    });

    $('#btnSave').click(function() {
        saveEditorContent();
    });

    updateSceneList();

    //setup autosave
    setInterval(function() {
        performAutosaveIfEnabled()
    }, autoSaveIntervalInSeconds * 1000);
}

