<?php
/**
 * Created by PhpStorm.
 * User: Amir
 * Date: 9/14/2019
 * Time: 6:56 PM
 */

namespace ProjectSetup;


use framework\core\BaseProject;
use framework\database\connection\MysqlDatabaseConnection;
use framework\debug\EchoLogger;
use framework\debug\Logger;

class TelegramBotSetupProject extends BaseProject {
  public $bot_api_key;
  public $bot_username;

  public $dbCredentials;

  public $debug_mode;
  public $debug_db;

  public $use_on;

  public $logger;

  private $isConfigured = false;

  public function instantiateScriptConfig() {
    if ($this->isConfigured) return;
    $this->logger = EchoLogger::getInstance();
    $this->loadConfigsFrommJSON();
    MysqlDatabaseConnection::setDefaultCredentials($this->dbCredentials);
    $this->isConfigured = true;
  }

  /**
   * @return Logger
   */
  public function getLogger() {
    return $this->logger;
  }

  private function initDBCredentialsFromFile() {
    return json_decode(file_get_contents($this->getDbCredentialsFile()));
  }

  private function initTelegramBotApi() {
    $json_decode = json_decode(file_get_contents($this->getTelegramBotCredentialsFile()));
    return [$json_decode->bot_username, $json_decode->bot_api_key];
  }

  public function verboseDB() {
    return $this->debug_db;
  }

  public function debugEnabled() {
    $this->debug_mode;
  }

  public function verboseEnabled() {
    return true;
  }

  private function initHostConfig() {
    $json_decode = json_decode(file_get_contents($this->getHostConfigFile()));
    return [
        $json_decode->debug_mode,
        $json_decode->debug_db
    ];
  }

  private function setupFromConfig() {
    $json_decode = json_decode(file_get_contents($this->getMainConfigFile()));
    return [
        $json_decode->use_on
    ];
  }

  private function getMainConfigFile() {
    return __DIR__ . "/credentials/config.json";
  }

  private function getHostConfigFile() {
    return __DIR__ . "/credentials/host/$this->use_on.json";
  }

  private function getDbCredentialsFile() {
    return __DIR__ . "/credentials/db/$this->use_on.json";
  }

  private function getTelegramBotCredentialsFile() {
    return __DIR__ . "/credentials/bot/$this->use_on.json";
  }

  private function loadConfigsFrommJSON() {
    list($this->use_on) = $this->setupFromConfig();
    list(
        $this->debug_mode,
        $this->debug_db
        ) = $this->initHostConfig();
    if ($this->debug_mode) {
      ini_set("display_errors", true);
    }

    $this->dbCredentials = $this->initDBCredentialsFromFile();
    list($this->bot_username, $this->bot_api_key) = $this->initTelegramBotApi();
  }

}