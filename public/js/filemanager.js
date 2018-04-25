function fileManagerPopUp(field_id) {
    var fmurl = "/filemanager/dialog.php?popup=1&editor=mceu_25&lang=pl&field_id="+field_id;
    var newwindow = window.open(fmurl, 'Menadżer zawartości', 'height=600,width=720,scrollbars=yes,resizable=yes');
    if (window.focus) { newwindow.focus(); }                                       
            return false;
}