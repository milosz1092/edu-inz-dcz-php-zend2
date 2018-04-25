<?php
namespace Application\Functions;

class DirectoryFunctions {
    static function deleteWithFiles($dirPath) {
        if (is_dir($dirPath)) {

            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                    $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                    if (is_dir($file)) {
                        self::deleteWithFiles($file);
                    } else {
                        unlink($file);
                    }
            }
            rmdir($dirPath);
        }
    }
}

?>