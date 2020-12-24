<?php

namespace VoiceTroll\model\table;


use framework\database\MySQLTable;

class DBRemovableMessage extends MySQLTable {
  const id="id";
  const key="key";
  const info="info";
  protected  $table=[
      self::_NAME=>"removable_message",
      self::FIELDS=>[
          "0"=>[
              self::_NAME=>self::id,
              self::NULLABLE=>false,
              self::TYPE=>self::TYPE_INT,
              self::INDEX=>self::INDEX_PRIMARY,
              self::AUTOINCREMENT=>true,
          ],
          "1"=>[
              self::_NAME=>self::key,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'64',
              self::NULLABLE=>true,
          ],
          "2"=>[
              self::_NAME=>self::info,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'64',
              self::NULLABLE=>true,
          ],
      ]
  ];
  private static $instance=null;

  /**
   * @return DBAccount
   */
  public static function getInstance() {
    if (self::$instance===null){
      $classname = __CLASS__;
      self::$instance=new $classname();
    }
    return self::$instance;
  }
}