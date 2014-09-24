$(document).ready(function() {
    /*tagTree=new TagTree();
    scriptEditor=new ScriptEditor();

    tagTree.initialize();
    scriptEditor.initialize();*/
    initializeScriptEditor();
    initializeComments();
    initializeTagTree();
    initializeLayout();
    
    setInterval(keepLock, 60*1000);

    function keepLock() {
		var url = "?r=ajax/keeplock&id="+scriptId;
		$.ajax({
	        type: "GET",
	        url: url,
	        dataType: "json",
	        success: function(json) {
	            if (json.status == "fail") {
	            	alert(jst("js", "Could not refresh write lock"));
	            }
	        },
	        error: function() {
	        	alert(jst("js", "Could not refresh write lock"));
	        }
	    });
	}
});