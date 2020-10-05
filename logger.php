#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');

class Logger{

    public $FILE;
    public $MACHINE;

    function __construct($filename){
        
        $this->FILE = fopen($filename.".log", "a") or die("Unable to open file!");

        fwrite($this->FILE, PHP_EOL);
        fwrite($this->FILE, date(DATE_RFC2822)." /START".PHP_EOL);
        fwrite($this->FILE, "=========================================================".PHP_EOL);

        // $this->MACHINE = new rabbitMQClient("testRabbitMQ.ini","loggerClient");
    }   

    function onError($errno, $errstr, $errfile, $errline){
        $string = $errfile.": ".$errline.": ".$errstr.". (errno: ".$errno."). ".PHP_EOL;
        fwrite($this->FILE, $string);
    }

    function logg($data){
        $bt = debug_backtrace()[0];
        $filepath = $bt['file'];
        $line = $bt['line'];

        $log = $filepath.": ".$line.": ".json_encode( $data ).PHP_EOL;
        fwrite($this->FILE, $log);    

        // $this->MACHINE->publish($log);
    }

    function __destruct(){        
        fwrite($this->FILE, "=========================================================".PHP_EOL);
        fwrite($this->FILE, date(DATE_RFC2822)." /END".PHP_EOL);
        // fwrite($this->FILE, PHP_EOL);

        fclose($this->FILE);
    }
}

?>