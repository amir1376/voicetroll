<?php

namespace VoiceTroll\config;

use framework\core\BaseProject;
use framework\database\connection\MysqlDatabaseConnection;
use framework\debug\Logger;

class TelegramBotProject extends BaseProject {
  public $bot_api_key;
  public $bot_username;
  public $baseConfig;
  public $dbCredentials;
  public $debug_mode = true;
  public $debugger;

  private $isConfigured = false;

  public $verbose_enabled;
  public $verbose_db;

  public function instantiateScriptConfig() {
    if ($this->isConfigured) return;
    $tg = json_decode(file_get_contents(__DIR__ . "/credential/telegram.json"));
    $this->bot_api_key = $tg->bot_api_key;
    $this->bot_username = $tg->bot_username;
    $this->baseConfig = json_decode(file_get_contents(__DIR__ . "/credential/base-config.json"));
    $this->dbCredentials = json_decode(file_get_contents(
            __DIR__ . "/credential/db.json"
        )
    );
    $this->verbose_db = $this->baseConfig->verbose_db;
    $this->debug_mode = $this->baseConfig->debug_mode;
    $this->verbose_enabled = $this->baseConfig->verbose_enabled;
    if ($this->verboseEnabled()) {
      require(__DIR__ . "/PHPIndividual/php_initialize.php");
    }
    if ($this->debugEnabled()) {
      ini_set("display_errors", true);
      ini_set("error_log", "/logs/php_error_in_debug.log");
    }
    MysqlDatabaseConnection::setDefaultCredentials($this->dbCredentials);
    $this->isConfigured = true;
  }

  /**
   * @return Logger
   */
  public function getLogger() {
    return $this->debugger;
  }

  public function verboseDB() {
    return $this->verbose_db;
  }

  public function debugEnabled() {
    return $this->debug_mode;
  }

  public function verboseEnabled() {
    return $this->verbose_enabled;
  }
}