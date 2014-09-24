$(document).ready(function() {
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
});
