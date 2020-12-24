<?php
namespace framework\database;
abstract class Database {
  abstract function create();
  abstract function drop();
}

class MYSQLDatabase extends Database {
  private $database;

  public function __construct($database) {
    $this->database = json_decode($database);
  }

  function create() {
    $query="CREATE DATABASE ".$this->database->name." ;";
  }

  function drop() {
    $query="DROP DATABASE ".$this->database->name." ;";
  }
}