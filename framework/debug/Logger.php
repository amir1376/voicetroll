<?php

namespace framework\debug;
abstract class Logger {
  public $showTag = true;
  public $showTime = true;
  public $showFileAndLine = true;

  abstract public function send($type, $message);
}