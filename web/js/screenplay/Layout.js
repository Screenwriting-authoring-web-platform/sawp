var commentsPixelWidth=300;
var editorPixelWidth=600;
var leftBarPixelWidth=300;
var leftBarMinimizedPixelWidth=0;


function updateLayout(){
    $(editorContainerDiv).width(editorPixelWidth+"px");
    $('.left-container').width(leftBarPixelWidth+"px");
    updateEditorCommentsDivWidth();
}

function updateEditorCommentsDivWidth(){
    var isOneCommentOpen = commentsContainerDiv.children().length >0;
    var isLeftBarOpen = $('.left-container').position().left == 0;
    var minPageWidth=editorPixelWidth+30;
    if(isOneCommentOpen){
        commentsContainerDiv.width(commentsPixelWidth+"px");
        editorAndCommentsContainerDiv.width((editorPixelWidth+commentsPixelWidth+10)+"px");
        minPageWidth+=commentsPixelWidth+30;
    }else{
        commentsContainerDiv.width("0px");
        editorAndCommentsContainerDiv.width((editorPixelWidth+30)+"px");
    }
   
    if(isLeftBarOpen){
        minPageWidth+=leftBarPixelWidth;
    }
    
    $('.page-container').css( "min-width", minPageWidth+"px");
}

function layoutShowTagtree(){
    layoutShowLeft();
    $('#tab_tagtree').show();
    $('#tab_scenelist').hide();
}
function layoutShowSceneList(){
    layoutShowLeft();
    $('#tab_tagtree').hide();
    $('#tab_scenelist').show();
}

function layoutHideLeft(){
    var leftBarLeft=-(leftBarPixelWidth-leftBarMinimizedPixelWidth);
    $('.left-container').animate({left:leftBarLeft+"px"});
    $('.right-container').animate({left:leftBarMinimizedPixelWidth+"px"});
    $('.left-container .container-main').fadeOut(400,updateLayout);
}

function layoutShowLeft(){
   $('.left-container').animate({left:"0px"});
    $('.right-container').animate({left:leftBarPixelWidth+"px"});
    $('.left-container .container-main').fadeIn(400,updateLayout);
}

function toggleNavbarVisibility(){
    var navDiv=$(".navbar-fixed-top");
    var pageDiv=$('.page-container');
    
    var isNavbarVisible=(navDiv.position().top === 0);
    
    if(isNavbarVisible){
        navDiv.animate({top:-(navDiv.height()+4)+"px"});
        pageDiv.animate({top:"0px"});
        $('#btnToogleNavbar > span').attr("class","glyphicon glyphicon-chevron-down");
    }else{
        navDiv.animate({top:0+"px"});
        pageDiv.animate({top:navDiv.height()+"px"});
        $('#btnToogleNavbar > span').attr("class","glyphicon glyphicon-chevron-up");
    }
}

function initializeLayout(){
    updateLayout();
    layoutShowTagtree();

    $('.layoutHideLeft').click(function(){
        layoutHideLeft();
    });
    
    $('.layoutShowTagtree').click(function(){
        layoutShowTagtree();
    });
    
    $('.layoutShowScenelist').click(function(){
        layoutShowSceneList();
    });

    $('#btnToogleNavbar').click(function(){
        toggleNavbarVisibility();
    });

}
