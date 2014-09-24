function jst(cat,name) {
    var translated = $.i18n._(name);
    if(translated=="")
        return name;
    return translated;
}