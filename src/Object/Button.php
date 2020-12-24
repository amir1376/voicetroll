<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/17/2019
 * Time: 9:55 PM
 */

namespace VoiceTroll\Object;


class Button implements Executable {
  /**
   * @var callable $executable (Button $param)
   */
  private $executable = null;
  /**
   * @var string $name
   */
  private $name;
  private $displayName;

  /**
   * Button constructor.
   * @param string $btnName
   * @param string $displayName
   * @param callable $executable
   */
  public function __construct(string $btnName, string $displayName,$executable = null) {
    $this->setName($btnName);
    $this->setExecutable($executable);
    $this->displayName = $displayName;
  }

  function execute() {
    if ($this->executable !== null) {
      call_user_func($this->executable, $this);
    }
  }

  /**
   * @param null|callable $executable
   */
  public function setExecutable( $executable) {
    $this->executable = $executable;
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name) {
    $this->name = $name;
  }

  /**
   * @return callable
   */
  public function getExecutable() {
    return $this->executable;
  }

  public function getDisplayName() {
    return $this->displayName;
  }


}