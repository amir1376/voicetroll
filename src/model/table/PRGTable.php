<?php


namespace VoiceTroll\model\table;


use framework\database\MySQLTable;

abstract class PRGTable extends MySQLTable {
  public static abstract function getInstance();
}