<?php

namespace VoiceTroll\model\table;
class DBChat extends PRGTable {
  const id="id";
  const user_id="user_id";
  const type="type";
  protected  $table=[
      self::_NAME=>"chat",
      self::FIELDS=>[
          "0"=>[
              self::_NAME=>self::id,
              self::NULLABLE=>false,
              self::TYPE=>self::TYPE_INT,
              self::INDEX=>self::INDEX_PRIMARY,
          ],
          "1"=>[
              self::_NAME=>self::user_id,
              self::TYPE=>self::TYPE_INT,
              self::NULLABLE=>true,
          ],
          "2"=>[
              self::_NAME=>self::type,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'12',
              self::NULLABLE=>true,
          ],
      ]
  ];
  private static $instance=null;

  /**
   * @return DBChat
   */
  public static function getInstance() {
    if (self::$instance===null){
      $classname = __CLASS__;
      self::$instance=new $classname();
    }
    return self::$instance;
  }
}