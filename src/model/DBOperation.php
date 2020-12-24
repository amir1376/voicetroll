<?php

namespace VoiceTroll\model;

use framework\database\connection\MysqlDatabaseConnection as DB;
use VoiceTroll\model\table\DBAccount;
use VoiceTroll\model\table\DBAccountSettings;
use VoiceTroll\model\table\DBChat;
use VoiceTroll\model\table\DBReceivedUpdates;
use VoiceTroll\model\table\DBRemovableMessage;
use VoiceTroll\model\table\DBSong;

class DBOperation {


  static function searchForSong($song) {
    return DB::getInstance()->query(
        "SELECT * FROM song WHERE `title`LIKE '%$song%' OR emoji LIKE '%$song%'"
    );
  }

  static function searchForSongToShowInList($song, $owner_id, $limit, $offset = 0) {
    return DB::getInstance()->query(
        "SELECT * FROM song WHERE `title`LIKE '%$song%' OR emoji LIKE '%$song%' and (`is_active`= 1 OR `owner_id`= $owner_id ) ORDER BY use_count DESC LIMIT $offset , $limit "
    );
  }

  static function searchForPopularSongs($limit) {
    return DB::getInstance()->query("SELECT * FROM song ORDER BY use_count DESC LIMIT $limit");
  }

  static function searchForUnconfirmedSongs(int $limit) {
    return DB::getInstance()->query("SELECT * FROM song WHERE is_active='0' ORDER BY `id` DESC LIMIT $limit");
  }

  static function searchForRejectedSongs(int $limit) {
    return DB::getInstance()->query("SELECT * FROM song WHERE is_active='-1' LIMIT $limit");
  }

  static function searchForNewestSongs(int $limit) {
    return DB::getInstance()->query("SELECT * FROM song WHERE is_active='1' LIMIT $limit");
  }

  static function getSongByTelegramFileId($telegramFileId) {
    return DB::getInstance()->first("SELECT * FROM song WHERE `t_file_id` = '$telegramFileId' ");
  }

  public static function setSongAllowed($t_file_id, $allowed, $confirmedBy) {

    return DB::getInstance()->first(DBSong::getInstance()->updateRecord([
        DBSong::is_active => $allowed,
        DBSong::confirmed_by => $confirmedBy
    ], [
        DBSong::t_file_id => $t_file_id
    ]));
  }

  static function selectAllSongs($song) {
    return DB::getInstance()->query("SELECT * FROM song WHERE 1");
  }

  static function addSongs($t_file_id,
                           $owner_di,
                           $title,
                           $emoji,
                           $type,
                           $duration,
                           $mimeType) {
//    $success = file_put_contents($song, $data);
//    if ($success === false) {
//      return false;
//    }]
    return DB::getInstance()->query(DBSong::getInstance()->insertRecord([
        DBSong::owner_id => $owner_di,
        DBSong::t_file_id => $t_file_id,
        DBSong::title => $title,
        DBSong::emoji => $emoji,
        DBSong::msg_type => $type,
        DBSong::mime_type => $mimeType,
        DBSong::duration => $duration,
        DBSong::use_count => 0,
        DBSong::is_active => 0,

    ]));
  }

  public
  static function getAccount($telegramId) {
    return DB::getInstance()->first(DBAccount::getInstance()->getRecord(true, [
        DBAccount::t_id => (int)$telegramId
    ]));
  }

  public
  static function getAccountByUserName($telUsername) {
    return DB::getInstance()->first(DBAccount::getInstance()->getRecord(true, [
        DBAccount::t_username => (string)$telUsername
    ]));
  }

  public static function getAccountSettings($accountId) {
    return DB::getInstance()->first(DBAccountSettings::getInstance()->getRecord(true, [
        DBAccountSettings::s_id => (int)$accountId
    ]));
  }

  public static function getAllAccountSettings() {
    return DB::getInstance()->first(DBAccountSettings::getInstance()->getRecord(
        true,
        true));
  }

  static function chatExistForUser($telegramId) {
    return DB::getInstance()->first(DBChat::getInstance()->getRecord(true, [
        DBChat::user_id => $telegramId
    ]));
  }


  public
  static function addChat($chat_id, $user_id, $type) {
    return DB::getInstance()->query(DBChat::getInstance()->insertRecord([
        DBChat::id => $chat_id,
        DBChat::user_id => $user_id,
        DBChat::type => $type,
    ]));
  }

  static function addAccount(
      $id,
      $username,
      $firstName,
      $familyName,
      $language
  ) {
    return DB::getInstance()->query(DBAccount::getInstance()->insertRecord([
        DBAccount::t_id => $id,
        DBAccount::t_username => $username,
        DBAccount::t_first_name => $firstName,
        DBAccount::t_last_name => $familyName,
        DBAccount::t_language_code => $language,
    ])
    );
  }


  static function getDirection(int $id = -1) {
    $first = DB::getInstance()->first("SELECT `direction` FROM account_settings WHERE `s_id` = '$id'");
    if (is_array($first)) {
      return $first["direction"];
    }
    return $first;
  }


  public static function addAccountSettings(
      $accountId
  ) {
    return DB::getInstance()->query(DBAccountSettings::getInstance()->insertRecord([
        DBAccountSettings::s_id => $accountId,
        DBAccountSettings::is_admin => 0,
        DBAccountSettings::is_blocked => 0,
        DBAccountSettings::account_level => 0,
    ]));
  }

  public static function updateDirection(int $s_id, string $direction) {
    return DB::getInstance()->query(
        DBAccountSettings::getInstance()->updateRecord([
            DBAccountSettings::direction => $direction
        ], [
            DBAccountSettings::s_id => $s_id
        ])
    );
  }

  public static function getAccountWithSettings($accountId) {
    return DB::getInstance()->first("SELECT a.* ,s.* FROM account a,account_settings s WHERE t_id='$accountId' AND a.t_id=s.s_id");
  }

  public static function getAllAccountWithSettings() {
    return DB::getInstance()->query("SELECT a.* ,s.* FROM account a,account_settings s WHERE a.t_id=s.s_id");
  }

  public static function selectUserLanguage($accountId, $language_code) {
    return DB::getInstance()->query("UPDATE account_settings SET `language_code`='$language_code' WHERE s_id='$accountId'");
  }

  public static function haveThisLanguage(string $text) {
    return DB::getInstance()->first("SELECT * FROM language WHERE `name`= '$text' OR `code` ='$text'");
  }


  public static function getDebuggerAccounts($useCache = true) {
    $fun = __FUNCTION__;
    if ($useCache && isset(self::$$fun)) {
      return self::$$fun;
    }
//    print_r( debug_backtrace());
    $mysqli_result = DB::getInstance()->query("SELECT * FROM account_debugger WHERE `status`='true' LIMIT 1");
    self::$$fun = $mysqli_result;
    return $mysqli_result;
  }

  public static function getLastUpdate() {
    $var = DB::getInstance()->first("SELECT id FROM received_updates ORDER BY id DESC LIMIT 0, 1");
    if (sizeof($var) === 0) return 0;
    else return $var["id"];
  }

  public static function addUpdate($id, $data) {
    return DB::getInstance()->query(
        DBReceivedUpdates::getInstance()->insertRecord([
            DBReceivedUpdates::id => $id,
            DBReceivedUpdates::data => $data,
        ])
    );
  }

  public static function updateAccount($updateData, $id) {
    return DB::getInstance()->query(DBAccount::getInstance()->updateRecord(
        $updateData,
        [DBAccount::t_id => $id]
    ));
  }

  public static function updateAccountUseCountAndSongUseCount(string $song_id, int $user_id) {
    $query = DB::getInstance()->query(
        DBSong::getInstance()->getRecord([
            DBSong::use_count
        ], [
            DBSong::id => $song_id
        ])
    );
    if ($query == null) {
      return false;
    }
    $query = DB::getInstance()->query(
        DBSong::getInstance()->updateRecord(
            "`" . DBSong::use_count . "` = `" . DBSong::use_count . "` + '1'"
            , [
            DBSong::id => $song_id
        ])
    );
    return $query;
  }

  public static function updateAccountSettings($updateData, $id) {
    return DB::getInstance()->query(DBAccountSettings::getInstance()->updateRecord(
        $updateData,
        [DBAccountSettings::s_id => $id]
    ));
  }

  public static function getAdmins() {
    return DB::getInstance()->query(DBAccountSettings::getInstance()->getRecord(
        true,
        "`" . DBAccountSettings::is_admin . "` > 0"
    ));

  }

  public static function registerRemovableMessage($key, string $msgReceivers) {
    if ($key !== null) {
      $record["key"] = $key;
    }
    $record["info"] = $msgReceivers;
    return DB::getInstance()->query(DBRemovableMessage::getInstance()->insertRecord($record));
  }

  public static function getRemovableMessage($key) {
    return DB::getInstance()->first(DBRemovableMessage::getInstance()->getRecord(
        [DBRemovableMessage::info], [DBRemovableMessage::key => $key]
    ));

  }

  public static function setAccountAdmin(int $userId, int $adminLevel) {
    return DB::getInstance()
        ->query(DBAccountSettings::getInstance()->updateRecord(
            [
                DBAccountSettings::is_admin => $adminLevel
            ], [
                DBAccountSettings::s_id => $userId
            ]
        ));
  }

  public static function getChat(int $userId) {
    return DB::getInstance()->first(
        DBChat::getInstance()->getRecord(true, [
            DBChat::id => $userId
        ])
    );
  }

  public static function updateSong(
      $id,$newData
  ) {
    return DB::getInstance()->query(
        DBSong::getInstance()->updateRecord(
            $newData,
            [
                DBSong::id => $id
            ]
        )
    );
  }


}
