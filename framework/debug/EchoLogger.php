<?php

namespace framework\debug;

class EchoLogger extends Logger {
  private static $instance;

  /**
   * @return EchoLogger
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new static();
    }
    return self::$instance;
  }

  /**
   * @param $type
   * @param string $message
   */
  public function send($type, $message) {
    $message = PHP_EOL . "$message";
    $type = strtolower($type);
    switch ($type) {
      case "info":
      case "debug":
      case "verbose":
      case "warning":
        echo $message;
        break;
      case "error":
        \error_log($message);
        break;
    }
  }

  public function warning($message, $time = null, $tag = null, $file = null, $line = null) {
    $this->addTime($message, $time);
    $this->addTag($message, $tag);
    $this->addFileAndLineIfNeeded($message, $file, $line);
    $this->send("warning", $message);
  }

  public function error($message, $time = null, $tag = null, $file = null, $line = null) {
    $this->addTime($message, $time);
    $this->addTag($message, $tag);
    $this->addFileAndLineIfNeeded($message, $file, $line);
    $this->send("ERROR", $message);
  }

  public function verbose($message, $time = null, $tag = null, $file = null, $line = null) {
    $this->addTime($message, $time);
    $this->addTag($message, $tag);
    $this->addFileAndLineIfNeeded($message, $file, $line);
    $this->send("VERBOSE", $message);
  }

  public function info($message, $time = null, $tag = null, $file = null, $line = null) {
    $this->addTime($message, $time);
    $this->addTag($message, $tag);
    $this->addFileAndLineIfNeeded($message, $file, $line);
    $this->send("INFO", $message);
  }

  public function debug($message, $time = null, $tag = null, $file = null, $line = null) {
    $this->addTime($message, $time);
    $this->addTag($message, $tag);
    $this->addFileAndLineIfNeeded($message, $file, $line);
    $this->send("DEBUG", $message);
  }

  public function addTag(&$message, $tag) {
    if (!$this->showTag) {
      return;
    }
    $message = " $tag:$message ";
  }

  private function addFileAndLineIfNeeded(&$message, $file, $line) {
    if (!$this->showFileAndLine) {
      return;
    }
    if ($file === null || $line === null) {
      return;
    }
    $message = $message." $file:$line " ;
  }

  private function addTime(&$message, $time) {
    if (!$this->showTime) {
      return;
    }
    if ($time === null){
      return;
    }
    $message =  $message." ".$time." " ;
  }
}
