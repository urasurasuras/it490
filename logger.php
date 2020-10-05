#!/usr/bin/php
<?php

class Logger{

    public $FILE;

    function __construct($filename){
        $this->FILE = fopen($filename.".log", "w") or die("Unable to open file!");
    }   

    function onError($errno, $errstr, $errfile, $errline){
        $string = "Errno: ".$errno.". ".$errfile.": ".$errline.": ".$errstr;
        $this->logg($errstr);
    }

    function logg($data){
        $bt = debug_backtrace()[0];
        $filepath = $bt['file'];
        $line = $bt['line'];
        fwrite($this->FILE, $filepath.": ".$line.": ".json_encode( $data ).PHP_EOL);    
    }

    function close_logger(){
        fclose($this->FILE);
    }
}

?>