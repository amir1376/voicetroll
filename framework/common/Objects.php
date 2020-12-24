<?php


namespace framework\common;


class Objects {
  public static function requireNotNull($table) {
    if ($table === null) {
      throw new \InvalidArgumentException("$table is null");
    }
  }
}