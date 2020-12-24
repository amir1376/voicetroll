<?php

namespace VoiceTroll\direction;

use framework\util\MSG;
use InvalidArgumentException;
use Longman\TelegramBot\Entities\Update;
use VoiceTroll\Object\Account;
use VoiceTroll\direction\page\BasePage;
use VoiceTroll\direction\page\StartPage;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;

class Direction {
  /**
   * @var array $direction
   * @var Update $update
   */
  private $direction;
  private $update;
  private $account;

  /**
   * Direction constructor.
   * @param Update $update
   * @param $account
   * @param string $directionJson
   */
  public function __construct(Update $update, Account $account, string $directionJson = null) {
    $steps = null;
    $this->update = $update;
    $this->account = $account;
    $this->direction = [];
    if (is_string($directionJson)) {
      // database removes escapes and json cant be decoded :((
      $steps = str_replace("\\", "\\\\", $directionJson);
      $steps = json_decode($steps, true);
    } else if (is_null($directionJson)) {
      $this->startAsNewDirection();
      return;
    } else {
      throw new InvalidArgumentException();
    }
    $length = sizeof($steps);
    MSG::messageForDebug($length);
    for ($i = 0; $i < $length; $i++) {
      /** @var BasePage $lastPage */
      //create Direction page object from a Path for now is mysql db
      $className = str_replace("|", "\\", $steps[$i]["class_name"]);
      if (!class_exists($className)) {
        MSG::messageForDebug("We broken in creating direction object $className");
        break;
      }
      /** @var BasePage $lastPage */
      $lastPage = new $className($this, $this->getUpdate(), $this->getAccount());
      $this->direction[] = $lastPage;
      $lastPage->addInputs($steps[$i]["inputs"]);
    }
    if (sizeof($this->direction) === 0) {
      $this->startAsNewDirection();
    }
  }

  public function isInRoot(): bool {
    return $this->getHeadIndex() === 0;
  }


  public function applyToDatabase() {
    DBOperation::updateDirection($this->update->getMessage()->getFrom()->getId(), $this->__toString());
  }


  function getForwardList() {
    return $this->getHeadPageWithParams()->getForwardPages();
  }

  /**
   * @return Update
   */
  public
  function getUpdate() {
    return $this->update;
  }

  public function __toString(): string {
    $this->TEST_TO_STRING();
    $out = array();
    /** @var BasePage $page */
    foreach ($this->direction as $page) {
      $p = array();
      $p["class_name"] = $page->getClassName();
      $p["inputs"] = $page->getInputs();
      $out[] = $p;
    }
    return json_encode($out, JSON_UNESCAPED_UNICODE);
  }

  /**
   * @return BasePage
   */
  public function getHeadPageWithParams() {
    return $this->direction[$this->getHeadIndex()];
  }

//  private $head = 0;

  public function goBack($forceBackToStartPage, $clearInputs = true, $data = null): bool {
    if (!$this->canGoBack($forceBackToStartPage)) return false;
    $this->removePageFromHead();
    $basePage = $this->getHeadPageWithParams();
    if (is_array($data)) {
      $basePage->addInputs($data);
    }
    $basePage->onPageOpen();
    return true;
  }

  public function navigateTo(string $class, $data = null) {
    /** @var BasePage $forwardPage */

    $forwardPage = new $class($this, $this->getUpdate(), $this->getAccount());
    if (is_array($data)) {
      $forwardPage->addInputs($data);
    }
    $this->addPageToHead($forwardPage);
    MSG::messageForDebug($this->getHeadPageWithParams()->getClassName());
    $forwardPage->onPageOpen();
    MSG::messageForDebug($this->getHeadPageWithParams()->getClassName());
  }

  public function getPages() {
    if ($this->isInRoot()) return [];
    $out = array();
    /** @var BasePage $step */
    foreach ($this->direction as $step) {
      $out[] = $step->getName($this->getUserLanguage());
    }
    return $out;
  }

  public function ignoreMyDirectionAndProceedFromBack() {
    $this->goBack(true);
  }

  public function startAsNewDirection() {
    $this->direction = array();
    $this->direction[] = new StartPage($this, $this->getUpdate(), $this->getAccount());
  }

  public function getHeadIndex() {
    return sizeof($this->direction) - 1;
  }

  public function canGoBack($forceBackToStartPage = false) {
    if ($this->getHeadIndex() - 1 < 0) return false;
    if ($this->getHeadIndex() - 1 == 0 && !$forceBackToStartPage) return false;
    return true;
  }

  private function addPageToHead(BasePage $forwardPage) {
    MSG::messageForDebug("Adding " . $forwardPage->getName($this->getUserLanguage()));
    $this->direction[$this->getHeadIndex() + 1] = $forwardPage;
  }

  private function removePageFromHead() {
    MSG::messageForDebug("REMOVING " . $this->getHeadPageWithParams()->getName($this->getUserLanguage()));
    unset($this->direction[$this->getHeadIndex()]);
  }

  private function TEST_TO_STRING() {
    /** @var BasePage $page */
    foreach ($this->direction as $page) {
      MSG::messageForDebug($page::getName($this->getUserLanguage()));
    }
  }

  public function getAccount() {
    return $this->account;
  }

  private function getUserLanguage() {
    return Language::getINSTANCE(
        $this->getAccount()->language_code,
        $this->getAccount()->t_language_code
    );
  }

  /**
   * @return BasePage|null
   */
  public function getBackPage() {
    $index = $this->getHeadIndex() - 1;
    if ($index<0){
      return null;
    }
    return $this->direction[$index];
  }

}