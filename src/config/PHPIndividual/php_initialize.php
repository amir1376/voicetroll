<?php
//$this->debugger =  framework\debug\FileLogger::getInstance();
//$this->debugger->setFileName(_root_path."/logs/php_verbose.log");
use framework\debug\EchoLogger;
$this->debugger = EchoLogger::getInstance();
$this->debugger->showTag=true;
$this->debugger->showTime=false;
