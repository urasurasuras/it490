#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
// Change extension to .inc when done with this lib

class LoggerClient{

    public $FILE;
    public $MACHINE;

    function __construct($filename){
        $filename = basename($filename);
        echo "Logging file: ".$filename.PHP_EOL;
		
		if (!file_exists('log')) {
			mkdir ('log');
		}
		if (!file_exists('log/local')) {
			mkdir ('log/local');
		}
		
        $this->FILE = fopen("log/local/".$filename.".log", "a") or die("Unable to open file!");

        fwrite($this->FILE, PHP_EOL);
        fwrite($this->FILE, date(DATE_RFC2822)." /START".PHP_EOL);
        fwrite($this->FILE, "=========================================================".PHP_EOL);
		
		$cfgDir = dirname(__FILE__)."/../cfg/";

        $this->MACHINE = new rabbitMQClient($cfgDir."testRabbitMQ.ini","loggerClient");
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

        $this->MACHINE->publish($log);
    }

    function __destruct(){        
        fwrite($this->FILE, "=========================================================".PHP_EOL);
        fwrite($this->FILE, date(DATE_RFC2822)." /END".PHP_EOL);
        // fwrite($this->FILE, PHP_EOL);

        fclose($this->FILE);
    }
}

class LoggerServer{
    public $FILE;

    function __construct($filename, $cfg){
        $filename = basename($filename);
        echo "Log listener: ".$filename.PHP_EOL;

		if (!file_exists('log')) {
			mkdir ('log');
		}
		if (!file_exists('log/dist')) {
			mkdir ('log/dist');
		}
		
        $this->FILE = fopen("../log/dist/".$filename.".log", "a") or die("Unable to open file!");


        fwrite($this->FILE, PHP_EOL);
        fwrite($this->FILE, date(DATE_RFC2822)." /START LOGGER SERVER".PHP_EOL);
        fwrite($this->FILE, "=========================================================".PHP_EOL);

		$cfgDir = dirname(__FILE__)."/../cfg/";

        $server = new rabbitMQServer($cfgDir."testRabbitMQ.ini",$cfg); 
        echo "loggerRabbitMQServer BEGIN".PHP_EOL;
        $server->process_requests(array($this, 'requestProcessor'));
    }

    function requestProcessor($data)
    {
        echo "received request".PHP_EOL;
        
        print_r($data);
        fwrite($this->FILE, $data);    

        // return $data;
        //return array("returnCode" => '0', 'message'=>"Server received request and processed");
    }
}

?>