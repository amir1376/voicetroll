<?php
require_once(__DIR__ . "/autoload.php");
$uri = getRequestUri();
if ($uri == "/i-am-telegram") {
  define("_root_path", __DIR__);
  require(__DIR__ . "/src/hook.php");
} else {
  echo "this is of limits";
  exit;
}
exit;
function getRequestUri() {
  return $_SERVER["REQUEST_URI"];
}