var tagTree = null;
var tagTreeRoot = null;
var editCategoryPopoverIsVisible = false;

var tagTreeHistory = {
    MAX_HISTORY_SIZE: 10,
    treeHistory: new Array(),
    index: -1,
    addToHistory:
            function(elem) {
                while (this.treeHistory.length > this.index + 1) {
                    this.treeHistory.pop();
                }
                if (this.treeHistory.length >= this.MAX_HISTORY_SIZE) {
                    this.treeHistory.shift();
                }
                this.treeHistory.push(JSON.stringify(elem));
                this.index = this.treeHistory.length - 1;
            },
    moveForward:
            function() {
                if (this.index < this.treeHistory.length - 1)
                    this.index = this.index + 1;
            },
    moveBackwards:
            function() {
                if (this.index > 0)
                    this.index = this.index - 1;
            },
    getElementFromHistory:
            function() {
                var elem = this.treeHistory[this.index];
                return JSON.parse(elem);
            }
};

/**
 * 
 * @returns {Array [[id,text],...] } Array containing ids and name pairs of all categories in the tree.
 */
function getCategoryNamesAndIdsAsArray() {
    var categoryNamesAndIds = [];
    tagTreeRoot.visit(function(node) {
        var nodeId = node.key; //remove leading underscore
        var nodeTitle = node.title;
        if (typeof nodeTitle === 'undefined')
            nodeTitle = "";
        if (node.folder)
            nodeTitle = "#" + nodeTitle;
        categoryNamesAndIds.push({id: nodeId, text: nodeTitle});
    });

    categoryNamesAndIds.sort(function(t1, t2) {
        return compareStrings(t1.text.toLowerCase(), t2.text.toLowerCase());
    });
    return categoryNamesAndIds;
}
/**
 * 
 * @param {type} categoryName
 * @returns {Boolean} Returns true if the tree contains a category called categoryName
 */
function checkCategoryExist(categoryName) {
    var categoryAlreadyExists = false;
    tagTreeRoot.visit(function(node) {
        if (node.title === categoryName) {
            categoryAlreadyExists = true;
        }
    });
    return categoryAlreadyExists;
}

/**
 * checks if the given String contains at least one Hashtag (#)
 * @param {String} text the text which should be ckecked
 * @returns {String} true if the text contains Hashtag false otherwise
 */
function containingHashtags(text) {
    var containsHashtag = false;
    for (var i = 0; i < text.length; i++) {
        if (text.charAt(i) === '#') {
            containsHashtag = true;
            break;
        }
    }
    return containsHashtag;
}

/**
 * Opens the popover for the given treeNode to edit the name and the color
 * @param {FancytreeNode} treeNode for which the popover should be shown
 * @param {boolean} true if the popover should be shown for a new node and false if it should be shown
 * @param {String} defaultName The default name which will be shown in the input field as the name
 * for an existing node. For both cases the title is adapted and for a new node the abort button will remove the node.
 * @returns {undefined} nothing
 */
function showCategoryEditPopover(treeNode, isNodeJustCreated, defaultName) {
    if (treeNode.parent !== tagTreeRoot && !editCategoryPopoverIsVisible) {
        //alert("you are not allowed to edit the root category");
        editCategoryPopoverIsVisible = true;
        //editCategoryPopoverIsVisible = true;
        var nodeSpanElement = $(treeNode.span);
        nodeSpanElement.popover({
            html: true,
            placement: 'auto left',
            trigger: 'manual',
            title: function() {
                return isNodeJustCreated ? jst("js", 'Create category') : jst("js", 'edit category');
            },
            content: function() {
                return $("#templates > .edit-tag-category").html();
            },
            container: 'body'
        });

        /* wait for popover to be ready */
        nodeSpanElement.on('shown.bs.popover', function() {
            var popoverDivElement = nodeSpanElement.data('bs.popover').tip();

            /* Set color and name for selected category in popover */
            var currentCategoryColor = treeNode.data.color;
            if (typeof (currentCategoryColor) == 'undefined')
                currentCategoryColor = '#d06b64';

            popoverDivElement.find('select[name="colorpicker"]').simplecolorpicker('selectColor', currentCategoryColor);
            popoverDivElement.find('input[name="tagName"]').val(defaultName);
            popoverDivElement.find('input[name="tagName"]').focus();
            popoverDivElement.find('input[name="tagName"]').select();

            /* Handle saveButton click */
            popoverDivElement.find('button[name="save"]').click(function() {
                if (popoverDivElement.find('input[name="tagName"]').val() !== "") {
                    if (!containingHashtags(popoverDivElement.find('input[name="tagName"]').val())) {
                        treeNode.setTitle(popoverDivElement.find('input[name="tagName"]').val());
                        treeNode.data.color = popoverDivElement.find('select[name="colorpicker"]').val();
                        treeNode.setActive();
                        nodeSpanElement.popover('destroy');
                        handleTagTreeChanged();
                        tagTreeHistory.addToHistory(tagTreeRoot.toDict(true));
                        editCategoryPopoverIsVisible = false;
                    }
                    else {
                        alert(jst("js","You have to remove the '#' in the Name"));
                    }
                }
                else {
                    alert(jst("js","You have to insert a name for the category"));
                }

            });
            popoverDivElement.find('button[name="abort"]').click(function() {
                nodeSpanElement.popover('destroy');
                if (isNodeJustCreated) { // if node has just been created and abort is pressed the node should be removed
                    treeNode.remove();
                    handleTagTreeChanged();
                }
                editCategoryPopoverIsVisible = false;
            });
        });

        nodeSpanElement.popover('show');
    }
}

function nodeIsCategory(nodeId) {
    return tagTree.getNodeByKey(nodeId).isFolder();
}

function createNewTagInCategory(categoryId, name) {
    var categoryNode = tagTree.getNodeByKey(categoryId);
    printKeys();
    var childNode = categoryNode.addChildren({
        title: "",
        folder: false,
        selected: false,
        key: generateUnusedCategoryId(),
        data: {color: categoryNode.data.color}
    });
    categoryNode.setExpanded(true);
    handleTagTreeChanged();
    showCategoryEditPopover(childNode, true, getSelectedText());

    console.log("new key: " + childNode.key);
    return childNode.key;
}

function printKeys() {
    console.log(jst("js", 'keys') + "-------------");
    tagTreeRoot.visit(function(node) {
        console.log(node.key);
    });
}

function generateUnusedCategoryId() {
    var categoryId = 1;
    while (tagTree.getNodeByKey("me" + categoryId) !== null)
        categoryId++;
    return "me" + categoryId;
}
/**
 * Creates JSON of a subTree
 * @param {FancytreeNode} Node which should be converted to JSON
 * @returns {String} JSON for the node and all of its children
 */
function createTreeJSON(node) {
    var d = node.toDict(true);
    return JSON.stringify(d);
}

/**
 * Stores the tagTree in the database
 * @returns {undefined} nothing
 */
function saveTreeAndHandleServerResponse() {
    var treeSaveAction = "?r=ajax/savetree&id=" + scriptId;
    var treeContent = createTreeJSON(tagTreeRoot);

    $.post(treeSaveAction, {'content': treeContent}, null, 'json')
            .fail(function() {
                alert(jst("js", 'Error while saving'));
            })
            .done(function(json) {
                if (json.status !== 'ok' && json.status !== 'unchanged')
                    alert(jst("js","Could not save tree: A server side error occured. Please contact an admin.\nStatus:") + json.status);
            });
}

/**
 * Handles changes to the tagTree. Save the modified tree and updates the script editor accordingly.
 * @returns {undefined} nothing
 */
function handleTagTreeChanged() {
    removeNoMoreExistingTagsFromEditor(getCategoryIds());
    saveTreeAndHandleServerResponse();
    updateCategoryColorsInEditor(getCategoryIdsAndColors());
}

/**
 * Returns an array with all category ids that exist in the tagtree.
 * @returns {Array<String>} Array with all category ids
 */
function getCategoryIds() {
    var ids = [];

    tagTreeRoot.visit(function(node) {
        ids.push(node.key);
    });
    return ids;
}

/**
 * Returns id and color pairs for all categories in the tagTree.
 * @returns {Array [[id,color],...] } Array containing ids and color pairs of all categories in the tree.
 */
function getCategoryIdsAndColors() {
    var idsAndColors = [];

    tagTreeRoot.visit(function(node) {
        idsAndColors.push({id: node.key, color: node.data.color});
    });
    return idsAndColors;
}

/**
 * Deletes the selcted node except for the root node
 * @returns {undefined}
 */
function deleteSelectedNode() {
    var node = tagTree.getActiveNode();
    if (node.parent === tagTreeRoot) {
        //alert(jst("js",'you are not able to delete the root'));
    }
    else {
        var newNodeToSelect = node.getPrevSibling();
        if (newNodeToSelect === null)
            newNodeToSelect = node.getParent();
        node.remove();
        handleTagTreeChanged();
        tagTreeHistory.addToHistory(tagTreeRoot.toDict(true));
        newNodeToSelect.setActive();
    }
}

function addCategoryAtSelectedNode(categoryName) {
    var selectedNode = tagTree.getActiveNode();

    if (selectedNode !== null && !selectedNode.isFolder()) {
        while (selectedNode !== null && !selectedNode.isFolder())
            selectedNode = selectedNode.parent;
    }

    if (selectedNode === null)
        selectedNode = tagTreeRoot;

    var childNode = selectedNode.addChildren({
        title: categoryName,
        folder: true,
        selected: false,
        key: generateUnusedCategoryId(),
        data: {color: selectedNode.data.color}
    });
    selectedNode.setExpanded(true);
    handleTagTreeChanged();
    return childNode;
}

function test_fkt() {
    //alert(tagTree.getActiveNode().toString());
    //tagTree.getActiveNode().setFocus();
}

/**
 * Initalize the tagTree.
 * @returns {undefined} nothing
 */
function initializeTagTree() {
    var treeLoadAction = "?r=ajax/gettree&id=" + scriptId;


    /* create fancytree */
    $('#tagcategories').fancytree({
        extensions: ["edit", "dnd", "childcounter"],
        source: {
            url: treeLoadAction,
            cache: false,
            complete: function() {
                //update the script colors
                updateCategoryColorsInEditor(getCategoryIdsAndColors());
                tagTreeHistory.addToHistory(tagTreeRoot.toDict(true));
                tagTreeRoot.getFirstChild().setActive();
            }
        },
        childcounter: {
            deep: true,
            hideZeros: true,
            hideExpanded: true
        },
        edit: {
            triggerCancel: ["tab", "click"],
            triggerStart: isObserver ? null : ["dblclick", "f2", "shift+click", "mac+enter"],
            beforeEdit: function(event, data) {
                showCategoryEditPopover(data.node, false, data.node.title);
                return false;
            }
        },
        dnd: {
            autoExpandMS: 400,
            draggable: {
                zIndex: 1000,
                scroll: false
            },
            preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
            preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
            dragStart: function(node, data) { // Everything is draggable  
                return !isObserver;
                //return true;
            },
            dragEnter: function(node, data) {
                if (!node.folder) { // prevent dropping node below an object
                    return false;
                }
                if (node.parent === tagTreeRoot) {
                    return false;
                }
                return true;
            },
            dragDrop: function(node, data) {
                data.otherNode.moveTo(node, data.hitMode);
                handleTagTreeChanged();
                tagTreeHistory.addToHistory(tagTreeRoot.toDict(true));
            }
        },
        //checkbox: true,
        //selectMode: 3,
        clickFolderMode: 1,
        keyboard: true,
        autoActivate: true
    });
    tagTree = $('#tagcategories').fancytree('getTree');
    tagTreeRoot = tagTree.rootNode;



    $('#btn_add_category').click(function() {
        var newCategoryNode = addCategoryAtSelectedNode(jst("js", 'New category'));
        showCategoryEditPopover(newCategoryNode, true, "");
        //pushJSONtoHistory();
    });

    $('#btn_delete_category').click(function() {
        deleteSelectedNode();
    });

    $('#btn_undo_tagTree').click(function() {
        tagTreeHistory.moveBackwards();
        var d = tagTreeHistory.getElementFromHistory();
        tagTree.reload(d);
        tagTreeRoot.getFirstChild().setActive();
    });

    $('#btn_redo_tagTree').click(function() {
        tagTreeHistory.moveForward();
        var d = tagTreeHistory.getElementFromHistory();
        tagTree.reload(d);
        tagTreeRoot.getFirstChild().setActive();
    });
}