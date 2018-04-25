<?php
namespace Center\Model;

class ConstStorage {
    const EXT_IMG = array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg' ); //Images
    const EXT_FILE = array( 'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'xhtml', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'mdb', 'accdb', 'ppt', 'pptx', 'odt', 'ots', 'ott', 'odb', 'odg', 'otp', 'otg', 'odf', 'ods', 'odp', 'css', 'ai' ); //Files
    const EXT_VIDEO = array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" ); //Video
    const EXT_AUDIO = array( 'mp3', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' ); //Audio
    const EXT_MISC = array( 'zip', '7zip', '7z', 'rar', 'gz', 'tar', 'iso', 'dmg', 'img' ); //Archives
    const AVAILABLE_UPLOAD_EXTENSIONS = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'xhtml', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'mdb', 'accdb', 'ppt', 'pptx', 'odt', 'ots', 'ott', 'odb', 'odg', 'otp', 'otg', 'odf', 'ods', 'odp', 'css', 'ai', 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm", 'mp3', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav', 'zip', '7zip', '7z', 'rar', 'gz', 'tar', 'iso', 'dmg', 'img' ); 
    
    const MAX_DOCUMENT_UPLOAD_FILESIZE = 60;

}