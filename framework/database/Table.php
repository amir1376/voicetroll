<?php
namespace framework\database;

abstract class Table {
  const _NAME="name";
  const FIELDS="fields";
  const NULLABLE="nullable";
  const TYPE="type";
  const TYPE_VARCHAR="VARCHAR";
  const TYPE_TEXT="TEXT";
  const TYPE_INT="INT";
  const TYPE_TINYINT="TINYINT";
  const TYPE_BOOLEAN="BOOLEAN";
  const TYPE_JSON="JSON";
  const LENGTH="length";

  const ATTR = "attribute";
  const ATTR_UNSIGNED_ZEROFILL= "unsigned_zerofill";
  const ATTR_UNSIGNED= "unsigned";


  const AUTOINCREMENT="auto_increment";

  const INDEX="index";
  const INDEX_PRIMARY="primary";
  const INDEX_UNIQUE="unique";
  const INDEX_INDEX="index";
  const INDEX_FULLTEXT="fulltext";
  const INDEX_SPATIAL="spatial";



  protected abstract function getTable();
  abstract function createTable();
  abstract function dropTable();
  abstract function insertRecord($record);
  abstract function deleteRecord($whereConditions);
  abstract function updateRecord($newData, $whereConditions);
  abstract function getRecord($columns, $whereConditions);
  protected function getTableName() {
    return $this->getTable()[self::_NAME];
  }
  protected function getTableFields() {
    return $this->getTable()[self::FIELDS];
  }
  protected function getField($name) {
    return $this->getTableFields()[$name];
  }
  protected function isFieldAutoIncrement($field) {
    return isset($field[self::AUTOINCREMENT]) && $field[self::AUTOINCREMENT] === true;
  }
  protected function isFieldIndex($field) {
    if ( isset($field[self::INDEX]) && $field[self::INDEX]!==null){
      return $field[self::INDEX];
    };
    return false;
  }
  protected function isFieldNullable($field) {
    return isset($field[self::NULLABLE]) && $field[self::NULLABLE] == true;
  }
  protected function getFieldType($field) {
    if ( isset($field[self::TYPE]) && $field[self::TYPE]!==null){
      return $field[self::TYPE];
    };
    return false;
  }
  protected function getFieldLength($field) {
    if ( isset($field[self::LENGTH]) && $field[self::LENGTH]!==null){
      return $field[self::LENGTH];
    };
    return false;
  }
  protected function getFieldName($field) {
    if ( isset($field[self::_NAME]) && $field[self::_NAME]!==null){
      return $field[self::_NAME];
    };
    return false;
  }

  protected abstract function getUpdateSection($newData);
}


