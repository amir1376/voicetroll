<?php

namespace VoiceTroll\Object;


use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;
use VoiceTroll\model\DBOperation;

class Account {
  private $t_backup;
  private $s_backup;

  public $t_id;
  public $t_username;
  public $t_first_name;
  public $t_last_name;
  public $t_language_code;

  public $s_id;
  public $language_code;
  public $is_admin;
  public $is_blocked;
  public $account_level;
  public $direction;

  public static function createAnAccountIfNotExistsAndReturnAccount(Update $update, User $user) {
    $t_account = DBOperation::getAccount($user->getId());
    $accountSettings = DBOperation::getAccountSettings($user->getId());
    if ($t_account == false) {
      $accountCreated = DBOperation::addAccount(
          $user->getId(),
          $user->getUsername(),
          $user->getFirstName(),
          $user->getLastName(),
          $user->getLanguageCode()
      );
      $accountCreated != false && $t_account = DBOperation::getAccount($user->getId());
    }
    if ($accountSettings == false) {
      $accountSettingsCreated = DBOperation::addAccountSettings(
          $user->getId()
      );
      $accountSettingsCreated != false && $accountSettings = DBOperation::getAccountSettings($user->getId());
    }
    if ($t_account != null && $accountSettings != null) {
      $obj = new Account();
      $obj->t_backup = $accountSettings;
      $obj->wrap($t_account);
      $obj->s_backup = $accountSettings;
      $obj->wrap($accountSettings);
      return $obj;
    } else {
      return false;
    }
  }

  private function __construct() {
  }

  public function applyChangesToDatabaseIfSomethingChanges() {
    $accountChanges = array();
    $accountSettingsChanges = array();
    foreach ($this->t_backup as $key => $value) {
      if ($this->$key !== $this->t_backup[$key]) {
        $accountChanges[$key] = $this->$key;
      }
    }
    foreach ($this->s_backup as $key => $value) {
      if ($this->$key !== $this->s_backup[$key]) {
        $accountSettingsChanges[$key] = $this->$key;
      }
    }
    if (sizeof($accountChanges) > 0) {
      DBOperation::updateAccount(
          $accountChanges,
          $this->t_id
      );
    }
    if (sizeof($accountSettingsChanges) > 0) {
      DBOperation::updateAccountSettings(
          $accountChanges,
          $this->s_id
      );
    }
  }

  private function wrap($result) {
    $get_object_vars = get_object_vars($this);
    unset($get_object_vars["t_backup"]);
    unset($get_object_vars["s_backup"]);
    foreach ($get_object_vars as $key => $value) {
      if (!key_exists($key, $result)) {
//        MSG::messageForDebug("we have new field in database that not updated here: $key");
        continue;
      }
      $this->$key = $result[$key];
    }
  }

  /**
   * @return array
   */
  public function getTBackup() {
    return $this->t_backup;
  }

  /**
   * @return array
   */
  public function getSBackup() {
    return $this->s_backup;
  }

  /**
   * @return int
   */
  public function getIsAdmin() {
    return (int)$this->is_admin;
  }

  /**
   * @param int $is_admin
   */
  public function setIsAdmin($is_admin) {
    $this->is_admin = (string)$is_admin;
  }
}