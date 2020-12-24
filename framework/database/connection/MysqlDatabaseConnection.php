<?php

namespace framework\database\connection;

use Exception;
use framework\common\Connection;
use framework\core\BaseProject;
use framework\util\MSG;
use mysqli;

class MysqlDatabaseConnection implements Connection {
  /**
   * @return mysqli
   */
  function getConnection() {
    return $this->connection;
  }

  /**
   * @var mysqli $connection
   */
  private $connection;
  /**
   * @var object $credentials
   */
  private $credentials;
  /**
   * @var MysqlDatabaseConnection $Instance
   */
  private static $Instance;
  /**
   * @var array $defaultCredentials
   */
  private static $defaultCredentials;

  /**
   * @param array $credentials
   */
  public static function setDefaultCredentials($credentials) {
    self::$defaultCredentials = $credentials;
  }

  /**
   * @param null|array $credentials
   * @return MysqlDatabaseConnection
   * @throws Exception
   */
  public static function getInstance($credentials = null) {
    if (self::$Instance == null) {
      self::$Instance = new MysqlDatabaseConnection($credentials === null ? self::$defaultCredentials : $credentials);
    }
    if (self::$Instance->isConnected() == false) {
      self::$Instance->connect();
    }
    return self::$Instance;
  }

  public function __construct($credentials) {
    $this->credentials = $credentials;
  }

  private $isConnected = false;

  public function connect() {
    if ($this->isConnected()) return;
    if ($this->connection === null) {
      $this->connection = new mysqli(
          $this->credentials->host_name,
          $this->credentials->username,
          $this->credentials->password,
          $this->credentials->database_name,
          $this->credentials->port
      );
    } else {
      $this->connection->connect(
          $this->credentials->hostname,
          $this->credentials->username,
          $this->credentials->password,
          $this->credentials->databasename,
          $this->credentials->port
      );
    }
    if ($this->connection->connect_error) {
      throw new Exception("Cant connect to database");
    }
//    $this->connection->query("SET NAMES 'utf8'");
    $this->connection->query("SET NAMES 'utf8mb4'");
    $this->isConnected = true;
  }

  public function disconnect() {
    $this->connection->close();
  }

  public function isConnected(): bool {
    return $this->isConnected;
  }

  public function first($query) {
    $mysqli_result = $this->query($query);
    if (is_bool($mysqli_result)) {
      return $mysqli_result;
    }
    if (isset($mysqli_result[0])) return $mysqli_result[0];
    else return null;
  }

  /**
   * @param $query
   * @return array|bool
   */
  public function query($query) {
    $mysqli_result = $this->connection->query($query);
    if (BaseProject::getInstance()->verboseDB()) {
      MSG::messageForDebug("$query". " ".gettype($mysqli_result).":".(is_bool($mysqli_result)?(int)$mysqli_result:""));
    }
    if (!$mysqli_result) {
      if (BaseProject::getInstance()->verboseDB()) {
        MSG::messageForDebug("Query : " . $query . " and error : '" . mysqli_error($this->connection) . "'");
      }
    }
    if (is_bool($mysqli_result)) {
      return $mysqli_result;
    }
//        if ($mysqli_result->num_rows == 0) {
//            return null;
//        }

    $rows = array();
    while ($row = $mysqli_result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  }
}