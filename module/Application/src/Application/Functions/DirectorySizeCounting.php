<?php
namespace Application\Functions;

class DirectorySizeCounting {
    private $units;
    public $size;

    public function __construct() {
        $this->units = explode(' ', 'B KB MB GB TB PB');
    }
    
    public function dirsize($dir) {
        if(is_file($dir)) return array('size'=>filesize($dir),'howmany'=>0);
        if($dh=opendir($dir)) {
            $size=0;
            $n = 0;
            while(($file=readdir($dh))!==false) {
                if($file=='.' || $file=='..') continue;
                $n++;
                $data = $this->dirsize($dir.'/'.$file);
                $size += $data['size'];
                $n += $data['howmany'];
            }
            closedir($dh);
            return array('size'=>$size,'howmany'=>$n);
        } 
        return array('size'=>0,'howmany'=>0);
    }
    
    public function countSize($path) {
        $this->size = 0;
        $size_tmp = 0;
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $size_array = $this->dirsize($path);
            $size_tmp = $size_array['size'];
        }
        else {
            $io = popen('/usr/bin/du -sb '.$path, 'r');
            $size_tmp = intval(fgets($io, 128));
            pclose($io);
        }
        
        $this->size = $size_tmp;
        return $size_tmp;
    }


    public function format_size($size = 0) {
        $mod = 1024;
        
        if ($size == 0) {
            $size = $this->size;
        }

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        $endIndex = strpos($size, ".")+3;

        return substr($size, 0, $endIndex).' '.$this->units[$i];
    }
}

?>