#!/usr/bin/php
<?php

class Logger{

    public $FILE;

    function __construct($filename){
        $this->FILE = fopen($filename.".log", "w") or die("Unable to open file!");
    }   

    function onError($errno, $errstr, $errfile, $errline){
        $string = "Errno: ".$errno.". ".$errfile.": ".$errline.": ".$errstr;
        $this->logg($string);
    }

    function logg($string){
        // $string = "Errno: ".$errno.". ".$errfile.": ".$errline.": ".$errstr;
        fwrite($this->FILE, $string.PHP_EOL);    
    }

    function close_logger(){
        fclose($this->FILE);
    }
}

?>