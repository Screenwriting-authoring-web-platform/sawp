var commentThreadVisible = false;
var commentsPixelWidth = 300;


/**
 * Creates a div container for a comment at the height of the selction on the right side of the editor.
 * @returns {jqueryNode} jquery node wrapping the div element for the comment.
 */
function createDivForComment() {
    var selectionBounds = getSelectionBoundsRelativeToEditorContainerDiv();
    var absolutePositionedDiv = $('<div style="position:absolute;top:' + selectionBounds.y + 'px;left:0px; width:' + commentsPixelWidth + 'px;"></div>');
    commentsContainerDiv.append(absolutePositionedDiv);
    return absolutePositionedDiv;
}

function bindCommentAnchors() {
    $("#editor_ifr").contents().find(".mce-item-anchor").click(function() {
        var top = $('.editor-container').offset().top + $(this).position().top;
        var id = $(this).attr("name");
        showCommentThread(top, id);
    });
}


function showCommentThread(top, id) {
    if (commentThreadVisible === true)
        return;
    commentThreadVisible = true;

    var showCommentBox = createDivForComment();
    showCommentBox.addClass("panel panel-default");
    showCommentBox.html('<div class="panel-heading">' + jst("js", 'Comment') + '\
    <button type="button" id="closeCommentButton" class="btn pull-right btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button>\
    </div><div class="panel-body">\
    <center><img src="media/spinner.gif"></center>\
    </div>');

    updateLayout();

    $('#closeCommentButton').click(function() {
        showCommentBox.remove();
        updateLayout();
        commentThreadVisible = false;
    });

    var staticContent = "";
    if (!isObserver)
        staticContent += '<button type="button" id="deleteThreadButton" class="btn top-buffer pull-left  btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span> ' + jst("js", 'delete Thread') + '</button>';

    staticContent += '<button type="button" id="showCreateCommentButton" class="btn top-buffer pull-right btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> ' + jst("js", 'reply') + '</button>\
    <div id="commentReply" style="display: none;"><textarea id="createCommentTextarea" cols="30" rows="6"></textarea><br />\
    <button type="button" id="createCommentButton" class="btn top-buffer pull-right btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> ' + jst("js", 'Add') + '</button></div>';

    var url = "?r=ajax/getthread&id=" + id;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(json) {
            var content = "";
            var lastId = id;
            $(json).each(function() {
                var username = this.username;
                if (username.length > 12)
                    username = username.substring(0, 12) + "&#8230;";
                content += '<p style="word-wrap: break-word;">' + this.text + "</p>\n";
                content += '<p>' + this.creationtime;
                content += " <span style=\"float: right; \">" + '<a style="color: inherit" href="?r=user/view&id=' + this.userId + '">' + "<img src=\"" + this.usericon + "\"> ";
                content += username + "</a></span></p>";
                content += "<hr style=\"margin-bottom: 5px; margin-top: 5px; border-color: #aaa; clear: both;\" />\n";
                lastId = this.id;
            });
            $(showCommentBox).find(".panel-body").html(content + staticContent);
            $('#showCreateCommentButton').click(function() {
                $('#deleteThreadButton').hide();
                $(this).hide();
                $('#commentReply').show();
                $('#createCommentTextarea').focus();
            });
            $('#createCommentButton').click(function() { //reply
                var text = $('#createCommentTextarea').val();
                var url = "?r=ajax/createcomment";
                var data = {screenplayId: scriptId, text: text, parentId: lastId};

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: "json",
                    success: function(json) {
                        if (json.status == "ok") {
                            var y = showCommentBox.position().top;
                            showCommentBox.remove();
                            commentThreadVisible = false;
                            showCommentThread(y, id);
                        }
                    }
                });
            });
            $('#deleteThreadButton').click(function() { //delete Thread
                if (confirm(jst("js", 'Are you sure ?'))) {
                    var url = "?r=ajax/deletethread&id=" + id;
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        success: function(json) {
                            if (json.status == "ok") {
                                showCommentBox.remove();
                                commentThreadVisible = false;
                                updateLayout();
                                $("#editor_ifr").contents().find("a[name='" + id + "']").remove();
                                saveEditorContent();
                            } else {
                                alert(jst("js", 'Error while deleting Thread!'));
                            }
                        }
                    });
                }
            });
        },
        error: function(xhr, status, error) {
            $(showCommentBox).find(".panel-body").html(jst("js", 'Error while loading Thread!'));
        }
    });
}

function showCreateComment() { //show create initial comment/thread/anchor
    if (commentThreadVisible === true)
        return;
    commentThreadVisible = true;
    var createCommentBox = createDivForComment();

    createCommentBox.addClass("panel panel-default");
    createCommentBox.html('<div class="panel-heading">' + jst("js", 'Add Comment') + '\
    <button type="button" id="closeCommentButton" class="btn pull-right btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button>\
    </div><div class="panel-body">\
    <textarea id="createCommentTextarea" cols="30" rows="6"></textarea><br />\
    <button type="button" id="createCommentButton" class="btn top-buffer pull-right btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> ' + jst("js", 'Add') + '</button>\
    </div>');

    updateLayout();

    $('#createCommentTextarea').focus();

    $('#closeCommentButton').click(function() {
        createCommentBox.remove();
        updateLayout();
        commentThreadVisible = false;
    });

    $('#createCommentButton').click(function() { //click add on create initial comment/thread/anchor
        var text = $('#createCommentTextarea').val();
        var url = "?r=ajax/createcomment";
        var data = {screenplayId: scriptId, text: text, parentId: null};
        if (isObserver) {
            data.pos = getCursorPosition(tinyMCEInstance);
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(json) {
                if (json.status == "ok") { //add anchor
                    var id = json.commentId;

                    //find end of node or word, go to next node if its a textnode
                    var end = false;
                    tinyMCEInstance.selection.collapse(false);
                    while (!end) {
                        var selRng = tinyMCEInstance.selection.getRng();
                        if (selRng.endOffset < selRng.endContainer.textContent.length)
                            selRng.setEnd(selRng.endContainer, selRng.endOffset + 1);
                        if (selRng.toString() == " ") {
                            tinyMCEInstance.selection.collapse(true);
                            end = true;
                        }
                        else {
                            if (selRng.endContainer.nextSibling && selRng.endContainer.nextSibling.nodeName == "#text")
                                selRng.setEnd(selRng.endContainer.nextSibling, 0);
                            else
                                end = true;
                        }
                        tinyMCEInstance.selection.setRng(selRng);
                        tinyMCEInstance.selection.collapse(false);
                    }

                    tinyMCEInstance.execCommand('mceInsertContent', false, tinyMCEInstance.dom.createHTML('a', {name: id}));
                    if (!isObserver) {
                        saveEditorContent();
                    }
                    var y = createCommentBox.position().top;
                    createCommentBox.remove();
                    commentThreadVisible = false;
                    bindCommentAnchors();
                    showCommentThread(y, id);
                }
            }
        });

        function getCursorPosition(editor) {
            //set a bookmark so we can return to the current position after we reset the content later
            var bm = editor.selection.getBookmark(0);

            //select the bookmark element
            var selector = "[data-mce-type=bookmark]";
            var bmElements = editor.dom.select(selector);

            //put the cursor in front of that element
            editor.selection.select(bmElements[0]);
            editor.selection.collapse();

            //add in my special span to get the index...
            //we won't be able to use the bookmark element for this because each browser will put id and class attributes in different orders.
            var elementID = "######cursor######";
            var positionString = '<span id="' + elementID + '"></span>';
            editor.selection.setContent(positionString);

            //get the content with the special span but without the bookmark meta tag
            var content = editor.getContent({format: "html"});
            //find the index of the span we placed earlier
            var index = content.indexOf(positionString);

            //remove my special span from the content
            editor.dom.remove(elementID, false);

            //move back to the bookmark
            editor.selection.moveToBookmark(bm);

            return index;
        }

    });

}

function initializeComments() {
    $('#btnComment').click(function() {
        showCreateComment();
    });
}