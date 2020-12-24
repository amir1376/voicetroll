<?php
namespace ProjectSetup {
  spl_autoload_register(function ($name) {
    $slice = explode("\\", $name);
    if (!isset($slice[0])) return false;
    if ($slice[0] !== __NAMESPACE__) return false;
    $name = substr($name, strlen($slice[0]) + 1, strlen($name));
    $classPath = __DIR__ . "/" . str_replace("\\", "/", $name) . ".php";
//  echo $classPath;
//  echo PHP_EOL;
    if (is_file($classPath)) {
      require_once($classPath);
      return true;
    } else {
      return false;
    }
  });
}
