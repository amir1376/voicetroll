<?php

namespace VoiceTroll\model\table;
class DBSong extends PRGTable {
  const id="id";
  const owner_id="owner_id";
  const t_file_id="t_file_id";
  const title="title";
  const emoji="emoji";
  const use_count="use_count";
  const mime_type="mime_type";
  const is_active = "is_active";
  const msg_type = "msg_type";
  const confirmed_by = "confirmed_by";
  const duration = "duration";
  protected $table = [
      self::_NAME => "song",
      self::FIELDS => [
          "1" => [
              self::_NAME => self::id,
              self::NULLABLE => false,
              self::TYPE => self::TYPE_INT,
              self::LENGTH => "64",
              self::INDEX=>self::INDEX_PRIMARY,
              self::AUTOINCREMENT => true,
          ],
          "2" => [
              self::_NAME=>self::owner_id,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "64",
          ],
          "3" => [
              self::_NAME=>self::t_file_id,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "64",
          ],
          "4" => [
              self::_NAME=>self::title,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "64",
          ],
          "5" => [
              self::_NAME=>self::emoji,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "4",
          ],
          "6" => [
              self::_NAME=>self::use_count,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_INT,
              self::LENGTH => "10",
          ],
          "7" => [
              self::_NAME=>self::is_active,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_INT,
              self::LENGTH => 2,
          ],
          "8" => [
              self::_NAME=>self::mime_type,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "12",
          ],
          "9" => [
              self::_NAME=>self::msg_type,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_VARCHAR,
              self::LENGTH => "10",
          ],
          "10" => [
              self::_NAME=>self::duration,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_INT,
          ],
          "11" => [
              self::_NAME=>self::confirmed_by,
              self::NULLABLE => true,
              self::TYPE => self::TYPE_INT,
          ],
      ]
  ];
  private static $instance = null;

  /**
   * @return DBSong
   */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new DBSong();
    }
    return self::$instance;
  }
}