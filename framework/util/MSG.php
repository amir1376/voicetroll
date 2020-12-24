<?php

namespace framework\util;

use framework\core\BaseProject;
use framework\debug\EchoLogger;

class MSG {


  public static function makeMSGReady(&$message) {
    if (is_array($message) || is_object($message)) {
      $message = print_r($message, true);
    }

    return $message;
  }

  public static function messageForDebug($message = "", $tag = null) {
    list($file, $line) = self::getFileAndLine();
    self::makeMSGReady($message);
    /** @var EchoLogger $logger */
    $logger = BaseProject::getInstance()->getLogger();
    $logger->debug(
        $message, time(), $tag, $file, $line
    );
  }

  public static function messageForVerbose($message = "", $tag = null) {
    list($file, $line) = self::getFileAndLine();
    /** @var EchoLogger $logger */
    $logger = BaseProject::getInstance()->getLogger();
    $logger->verbose($message, time(), $tag, $file, $line);
  }

  public static function dump($val, $return = false) {
    if (is_array($val)) {
      $out = print_r($val, true);
    } else if (is_object($val)) {
      $out = var_export($val, true);
    } else {
      $out = $val;
    }
    $out = "\n<pre>$out</pre>\n";
    if ($return) {
      /** @var string $out */
      return $out;
    }
    echo $out;
  }

  private static function getFileAndLine() {
    $bt = debug_backtrace();
    //because of two data flow
    $caller = array_shift($bt);
    $caller = array_shift($bt);
    $file = $caller['file'];
    $line = $caller['line'];
    return [$file, $line];
  }
}