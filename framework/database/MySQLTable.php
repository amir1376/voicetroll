<?php

namespace framework\database;

use framework\common\Objects;

class MySQLTable extends Table {
  public static function createInstance($table = null) {
    $SQLTable = new MySQLTable();
    if ($table !== null) {
      $SQLTable->table = json_decode($table, true);
    }
    return $SQLTable;
  }

  protected $table;

  protected function __construct() {
  }

  function createTable(): string {
    Objects::requireNotNull($this->getTableName());
    $query = "";
    $query .= "CREATE TABLE";
    if (true) {
      $query .= " IF NOT EXISTS";
    }
    $query .= " " . $this->getTableName();
    $query .= " (";
    $isFirst = true;
    $tableFields = $this->getTableFields();
    foreach ($tableFields as $field) {
      if ($isFirst === false) {
        $query .= " ,";
      } else {
        $isFirst = false;
      }
      $query .= " `";
      $query .= $this->getFieldName($field);
      $query .= "` ";
      $query .= " " . $this->getFieldType($field);

      if ($len = $this->getFieldLength($field)) {
        $query .= " (";
        $query .= $len;
        $query .= ")";
      }
      if ($this->isFieldAutoIncrement($field)) {
        $query .= " " . $this->value_auto_increment();
      }
      if ($index = $this->isFieldIndex($field)) {
        $query .= " " . $this->value_index($index);
      }
      if ($this->isFieldNullable($field)) {
        $query .= " " . $this->value_NULL();
      } else {
        $query .= " " . $this->value_NOT_NULL();
      }
    }
    $query .= " )";
    return $query . ";";
  }

  function dropTable(): string {
    Objects::requireNotNull($this->getTable());
    $query = "DROP TABLE " . $this->getTableName() . " ;";
    return $query;
  }

  function insertRecord($record): string {
    Objects::requireNotNull($this->getTable());
    $query = "";
    $query .= "INSERT INTO " . $this->getTableName() . " SET ";
    $isFirst = true;
    $fields = $this->getTableFields();
    foreach ($fields as $field) {
      if (key_exists($this->getFieldName($field), $record)) {
        if ($isFirst) {
          $isFirst = false;
        } else {
          $query .= " , ";
        }
        $query .= "`" . $this->getFieldName($field) . "`" . " = " . ($record[$this->getFieldName($field)] === null ? $this->value_NULL() : "'" . $record[$this->getFieldName($field)] . "'");
      }
    }
    return $query;
  }

  function deleteRecord($whereConditions): string {
    Objects::requireNotNull($this->getTableName());
    $query = "DELETE FROM " . $this->getTableName();
    $query .= " WHERE ";
    $query .= $this->getWhereCondition($whereConditions);
    $query .= ";";
    return $query;
  }

  function updateRecord($newData, $whereConditions): string {
    Objects::requireNotNull($this->getTable());
    $query = "";
    $query .= "UPDATE " . $this->getTableName();
    $query .= $this->getUpdateSection($newData);
    $query .= " WHERE ";
    $query .= $this->getWhereCondition($whereConditions);
    $query .= ";";
    return $query;
  }

  function getRecord($columns, $whereConditions): string {
    Objects::requireNotNull($this->getTable());
    $query = "SELECT";
    if (is_bool($columns) && $columns === true) {
      $query .= " *";
    } elseif (is_array($columns)) {
      $isFirst = true;
      foreach ($columns as $column) {
        if ($isFirst) {
          $isFirst = false;
          $query .= " ";
        } else {
          $query .= " , ";
        }
        $query .= $column;
      }
    }
    $query .= " FROM " . "`" . $this->getTableName() . "`";
    $query .= " WHERE ";
    $query .= $this->getWhereCondition($whereConditions);
    $query .= ";";
    return $query;
  }

  protected function value_auto_increment() {
    return "AUTO_INCREMENT";
  }

  protected function value_index($index) {
    switch ($index) {
      case self::INDEX_PRIMARY:
        return "PRIMARY KEY";
      case self::INDEX_INDEX:
        return "INDEX";
      case self::INDEX_UNIQUE:
        return "UNIQUE";
      case self::INDEX_FULLTEXT:
        return "FULLTEXT";
      case self::INDEX_SPATIAL:
        return "SPATIAL";
      default :
        new \InvalidArgumentException("not supported");
    }
    return false;
  }

  private function value_NULL() {
    return "NULL";
  }

  private function value_NOT_NULL() {
    return "NOT NULL";
  }

  protected function getTable() {
    return $this->table;
  }

  private function getWhereCondition($whereConditions) {
    if (is_string($whereConditions)) return $whereConditions;
    if ($whereConditions === null) return 0;
    if (is_bool($whereConditions)) return $whereConditions;
    if (!is_array($whereConditions)) return 0;
    $isFirst = true;
    $where = "";
    $tableFields = $this->getTableFields();
    foreach ($tableFields as $field) {
      if (key_exists($this->getFieldName($field), $whereConditions)) {
        if ($isFirst) {
          $isFirst = false;
        } else {
          $where .= " AND ";
        }
        $where .= "`" . $this->getFieldName($field) . "`" . " = " . ($whereConditions[$this->getFieldName($field)] === null ? $this->value_NULL() : "'" . $whereConditions[$this->getFieldName($field)] . "'");
      }
    }
    return $where;
  }

  protected function getUpdateSection($newData) {
    $query = "";
    $query .= " SET";
    if (is_array($newData)) {
      $isFirst = true;
      $fields = $this->getTableFields();
      foreach ($fields as $field) {
        if (key_exists($this->getFieldName($field), $newData)) {
          if ($isFirst) {
            $isFirst = false;
          } else {
            $query .= " , ";
          }
          $query .= "`" . $this->getFieldName($field) . "`" . " = " . "'" . $newData[$this->getFieldName($field)] . "'";
        }
      }
    }else if (is_string($newData)){
      $query.=" ".$newData." ";
    }
    return $query;
  }
}