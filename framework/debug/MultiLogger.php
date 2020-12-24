<?php

namespace framework\debug;

class MultiLogger extends Logger {
  private static $instance;
  private $loggers = array();
  /**
   * @return MultiLogger
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new static();
    }
    return self::$instance;
  }

  public function send($type, $message) {
    /** @var Logger $logger */
    foreach ($this->loggers as $logger) {
      $logger->send($type, $message);
    }
  }
  /**
   * @param Logger $logger
   */
  public function addLogger(Logger $logger) {
    $this->loggers[] = $logger;
  }
  public function removeLogger(Logger $removeThisLogger) {
    foreach ($this->loggers as $key=>$logger) {
      if ($logger == $removeThisLogger){
        unset($this->loggers[$key]);
        return true;
      }
    }
    return false;
  }
  public function clearAllLoggers() {
    foreach ($this->loggers as $key=>$logger) {
        unset($this->loggers[$key]);
    }
  }
}