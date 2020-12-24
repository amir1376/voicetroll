<?php

namespace VoiceTroll\model\table;


use framework\database\MySQLTable;

class DBReceivedUpdates extends MySQLTable {
  const id="id";
  const data="data";
  protected  $table=[
      self::_NAME=>"received_updates",
      self::FIELDS=>[
          "0"=>[
              self::_NAME=>self::id,
              self::NULLABLE=>false,
              self::TYPE=>self::TYPE_INT,
              self::INDEX=>self::INDEX_PRIMARY,
          ],
          "1"=>[
              self::_NAME=>self::data,
              self::TYPE=>self::TYPE_TEXT,
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