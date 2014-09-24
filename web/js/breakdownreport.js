$(document).ready(function() {
    var foo = ".field-breakdownform-graphic, .field-breakdownform-charnotice, .field-breakdownform-scenes, .field-breakdownform-characters, .field-breakdownform-categories, .field-breakdownform-button, .selectallscenes, .selectallcategories, .deselectallscenes, .deselectallcategories";

    $(foo).each(function() {
        $(this).hide();
    });
    
    $("#breakdownform-type").val(0);
    
    $(".selectallscenes").click(function() {
        $("div.field-breakdownform-scenes").find(':checkbox').each(function(){
            jQuery(this).prop('checked', true);
        });
    });
    
    $(".selectallcategories").click(function() {
        $("div.field-breakdownform-categories").find(':checkbox').each(function(){
            jQuery(this).prop('checked', true);
        });
    });
    
    $(".deselectallscenes").click(function() {
        $("div.field-breakdownform-scenes").find(':checkbox').each(function(){
            jQuery(this).prop('checked', false);
        });
    });
    
    $(".deselectallcategories").click(function() {
        $("div.field-breakdownform-categories").find(':checkbox').each(function(){
            jQuery(this).prop('checked', false);
        });
    });
    
    $("#breakdownform-type").change(function() {
        var type = $(this).val();

        $(foo).each(function() {
            $(this).hide();
        });
        
        function show(selector) {
            $(selector).each(function() {
                $(this).show();
            });
        }

        switch(type) {
            case("0"): break;
            case("1"): 
                show(".field-breakdownform-graphic, .field-breakdownform-scenes, .field-breakdownform-categories, .field-breakdownform-button");
                $(".selectallscenes, .selectallcategories, .deselectallscenes, .deselectallcategories").css('display', 'inline-block');
                break;
            case("2"):
                show(".field-breakdownform-graphic, .field-breakdownform-scenes, .field-breakdownform-categories, .field-breakdownform-button");
                $(".selectallscenes, .selectallcategories, .deselectallscenes, .deselectallcategories").css('display', 'inline-block');
                break;
            case("4"):
                show(".field-breakdownform-charnotice, .field-breakdownform-categories, .field-breakdownform-button");
                $(".selectallcategories, .deselectallcategories").css('display', 'inline-block');
                break;
            default: break;
        }
    });
});
