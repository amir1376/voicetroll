<?php

namespace VoiceTroll\model\table;


use framework\database\MySQLTable;

class DBAccount extends MySQLTable {


  const t_id="t_id";
  const t_username="t_username";
  const t_first_name="t_first_name";
  const t_last_name="t_last_name";
  const t_language_code="t_language_code";


  protected  $table=[
      self::_NAME=>"account",
      self::FIELDS=>[
          "0"=>[
              self::_NAME=>self::t_id,
              self::NULLABLE=>false,
              self::TYPE=>self::TYPE_INT,
              self::INDEX=>self::INDEX_PRIMARY,
          ],
          "1"=>[
              self::_NAME=>self::t_username,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'64',
              self::NULLABLE=>true,
          ],
          "2"=>[
              self::_NAME=>self::t_first_name,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'64',
              self::NULLABLE=>true,
          ],
          "3"=>[
              self::_NAME=>self::t_last_name,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'64',
              self::NULLABLE=>true,
          ],
          "4"=>[
              self::_NAME=>self::t_language_code,
              self::TYPE=>self::TYPE_VARCHAR,
              self::LENGTH=>'3',
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