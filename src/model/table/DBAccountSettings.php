<?php

namespace VoiceTroll\model\table;


class DBAccountSettings extends PRGTable {
  const s_id = "s_id";
  const is_admin = "is_admin";
  const is_blocked = "is_blocked";
  const account_level = "account_level";
  const language_code = "language_code";
  const direction = "direction";

  protected $table = [
      self::_NAME => "account_settings",
      self::FIELDS => [
          "0" => [
              self::_NAME => self::s_id,
              self::NULLABLE => false,
              self::TYPE => self::TYPE_INT,
              self::INDEX => self::INDEX_PRIMARY,
          ],
          "1" => [
              self::_NAME => self::is_admin,
              self::TYPE => self::TYPE_INT,
            //todo add this to quetybuiler
              self::ATTR => self::ATTR_UNSIGNED_ZEROFILL,
              self::LENGTH => 1,
              self::NULLABLE => true,
          ],
          "2" => [
              self::_NAME => self::is_blocked,
              self::TYPE => self::TYPE_BOOLEAN,
              self::NULLABLE => true,
          ],
          "3" => [
              self::_NAME => self::account_level,
              self::TYPE => self::TYPE_TINYINT,
              self::NULLABLE => true,
          ],
          "4" => [
              self::_NAME => self::language_code,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => '3',
              self::NULLABLE => true,
          ],
          "5" => [
              self::_NAME => self::direction,
              self::TYPE => self::TYPE_TEXT,
              self::NULLABLE => true,
          ],
      ]
  ];
  private static $instance = null;

  /**
   * @return DBAccountSettings
   */
  public static function getInstance() {
    if (self::$instance === null) {
      $classname = __CLASS__;
      self::$instance = new $classname();
    }
    return self::$instance;
  }
}