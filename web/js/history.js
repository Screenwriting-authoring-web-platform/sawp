$(document).ready(function() {    
    $("#timeslider").on("change",function() {
        var index = $(this).val();
        
        var revisionHeader = $(this).data('revisionheaders')[index];
        
        console.log(revisionHeader);
        
        $('#historyform-revision').val(revisionHeader.id);
        $('#timestamp').html(revisionHeader.creation_time);
        
        var url = "?r=ajax/getscreenplaycontentbyrevision&id="+$('#historyform-screenplayid').val()+"&rid="+revisionHeader.id;

        $.ajax({
            type: "GET",
            url: url,
            dataType: "text",
            success: function(text) {
                $('#content').html(text);
            }
        });
    });
});