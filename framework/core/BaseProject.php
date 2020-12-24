<?php

namespace framework\core;


use framework\debug\Logger;

abstract class BaseProject {
  /**
   * @var BaseProject $instance
   */
  private static $instance = null;
  protected final function register() {
    self::$instance = $this;
  }
  /**
   * @return BaseProject
   */
  public static function getInstance(): BaseProject {
    if (self::$instance === null) {
      throw new \BadMethodCallException("instance of project is null first register instance to framework");
    }
    return self::$instance;
  }
  public function __construct($register = false) {
    $this->instantiateScriptConfig();
    if ($register) {
      $this->register();
    }
  }
  public abstract function instantiateScriptConfig();
  /**
   * @return Logger
   */
  public abstract function getLogger();
  public abstract function verboseEnabled();
  public abstract function verboseDB();
  public abstract function debugEnabled();
}