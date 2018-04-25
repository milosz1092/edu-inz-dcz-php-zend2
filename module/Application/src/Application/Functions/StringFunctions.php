<?php
namespace Application\Functions;

class StringFunctions {
    static function my_mb_ucfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1), 'utf-8');
        
        return $fc.mb_substr($str, 1);
    }
}

?>