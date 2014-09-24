function hijackSumbmit(form) {
    form.submit(function(event) {
        event.preventDefault();
        if(!window.hasOwnProperty('submitInProgress') || window.submitInProgress == false) {
            window.submitInProgress = true;
            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: form.serialize(),
                dataType: "text",
                success: function(text) {
                    $('.modal-content').html(text);
                    hijackSumbmit($('.modal-content').find('form'));
                    window.submitInProgress = false;
                },
                error: function(xhr, status, error) {
                    if(xhr.status!=302) {
                        $('.modal-content').html(getErrorModalContent(xhr.responseText));
                        $('.modal-content').addClass("panel-danger");
                        hijackSumbmit($('.modal-content').find('form'));
                        window.submitInProgress = false;
                    } else {
                        setTimeout(function(){
                            window.submitInProgress = false;
                            }, 500);
                    }
                }
            });
        }
    });
}

function showModal(text) {
    var oldmodal = $('#basicModal');
    if(oldmodal!=null) oldmodal.remove();
    
    text = '\
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">\
    <div class="modal-dialog">\
        <div class="modal-content">\
            '+text+'\
        </div>\
    </div>\
  </div>\
</div>';
    
    var modal = $(text);
    $('body').append(modal);
    modal.modal('show');
    modal.on('hidden.bs.modal', function () {
        window.location.hash = "";
        window.submitInProgress = false;
    })
    
    var form = modal.find('form');
    hijackSumbmit(form);
}

function getErrorModalContent(error) {
    text = '\
<div class="modal-header panel-heading">\
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
    <h4 class="modal-title" id="myModalLabel">'+jst("js","Error")+'</h4>\
</div>\
<div class="modal-body">\
'+error+'\
</div>\
<div class="modal-footer">\
    <button type="button" class="btn btn-default" data-dismiss="modal">'+jst("js","Close")+'</button>\
</div>';

    return text;
}

function showUrlInModal(url) {
    var a =  document.createElement('a');
    a.href = url;
    window.location.hash = "#!"+a.search;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "text",
        success: function(text) {
            showModal(text);
        },
        error: function(xhr, status, error) {
            if(xhr.status!=302) {
                showModal(xhr.responseText);
                $('.modal-content').html(getErrorModalContent(xhr.responseText));
                $('.modal-content').addClass("panel-danger");
            }
        }
        });
}

$(document).ready(function() {    
    $('a.showinmodal').each(function(index) {
        $(this).data("url", $(this).attr("href"));
        var a =  document.createElement('a');
        a.href = $(this).attr("href");
        $(this).attr("href", window.location.href+"#!"+a.search);
    });

    $('a.showinmodal').click(function() {
        showUrlInModal($(this).data("url"));
        return false;
    });

    if(window.location.hash.substring(0, 2) == "#!") {
        showUrlInModal(window.location.origin+window.location.pathname+window.location.hash.substring(2));
    }


});
