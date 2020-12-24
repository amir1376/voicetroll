<?php

namespace framework\debug;

class FileLogger extends EchoLogger {
  private static $instance;
  private $fileName;

  /**
   * @return FileLogger
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new FileLogger();
    }
    return self::$instance;
  }


  public function send($type, $message) {
    if ($this->fileName === null) {
      throw new \BadFunctionCallException("file name must be set for File Logger ...");
    }
    $msg = PHP_EOL . "$message";
    //todo ask to guys for acquire lock:next time wait for lock is opened?
    file_put_contents($this->fileName, $msg, FILE_APPEND);
  }

  /**
   * @return string
   */
  public function getFileName() {
    return $this->fileName;
  }

  /**
   * @param string $fileName
   */
  public function setFileName($fileName) {
    $this->fileName = $fileName;
  }
}