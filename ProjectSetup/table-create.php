<?php

namespace ProjectSetup;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/autoload.php';
require_once __DIR__ . '/autoload.php';
require_once(__DIR__ . "/../src/autoload.php");

use Exception;
use framework\database\connection\MysqlDatabaseConnection;
use framework\database\MySQLTable;
use framework\util\MSG;
use framework\util\StringUtils;
use RecursiveDirectoryIterator;
use VoiceTroll\model\table\PRGTable;

$setupProject = new TelegramBotSetupProject(true);
$setupProject->instantiateScriptConfig();
if (!$setupProject->debug_mode){
  try {
    $db = MysqlDatabaseConnection::getInstance();
    MSG::messageForDebug("Connected");
  } catch (Exception $a) {
    MSG::messageForDebug($a->getMessage());
    MSG::messageForDebug("exiting...");
    exit;
  }
}
$dir = new RecursiveDirectoryIterator(__DIR__ . "/../src/model/table");
/** @var MySQLTable[] $tb */
$tb = array();
foreach ($dir as $item) {
  $fname = basename($item);
  if (StringUtils::string_starts_with($fname, "DB")
      && StringUtils::string_ends_with($fname, ".php")) {
    $className = substr($fname, 0, strlen($fname) - strlen(".php"));
    $className = "VoiceTroll\\model\\table\\" . $className;
    /** @var PRGTable $className */
    if (class_exists($className)) {
      $tb[] = $className::getInstance();
      MSG::messageForDebug($className." exists");
    }else{
      MSG::messageForDebug($className." not exists");

    }
  }
}
/** @var MySQLTable $table */
$sum = 0;
foreach ($tb as $table) {
  if ($setupProject->debug_mode) {
    MSG::messageForDebug($table->createTable());
  } else {
    $result = $db->query($table->createTable());
    $sum += $result;
    MSG::messageForDebug($result);
  }
}
if ($sum === sizeof($tb)) {
  MSG::messageForDebug("created");
} else {
  MSG::messageForDebug("not created");
}
