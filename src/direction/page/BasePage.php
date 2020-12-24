<?php

namespace VoiceTroll\direction\page;

use framework\util\MSG;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use VoiceTroll\direction\Direction;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use VoiceTroll\Object\Account;
use VoiceTroll\Object\Button;

abstract class BasePage {
  public $MAX_COLUMN_SIZE = 3;

  const BT_BACK = "bt_back";


  /**
   * @var  Direction $direction
   */
  private $direction;
  private $update;
  /**
   * not instance just name
   * @var BasePage[] $forwardPages
   */
  protected $forwardPages = [];
  /**
   * @var Button[] $buttons
   */
  protected $buttons = [];
  protected $keyboard = null;
  protected $inputs = [];
  protected $nextExpectedInput = null;
  protected $account;

  /**
   * BasePage constructor.
   * @param Direction $direction
   * @param Update $update
   * @param Account $account
   */
  public function __construct(Direction $direction, Update $update, Account $account) {
    MSG::messageForDebug($this->getClassName() . "Created :)))))))");
    $this->direction = $direction;
    $this->update = $update;
    $this->account = $account;
  }

  public function onNewMessageReceived(Message $message) {
    if (!$this->isObjectValidate()){
      MSG::messageForDebug("object ".$this->getClassName()."not validate for showing");
      return false;
    }
    $this->initButtons(true);
    if ($message->getType() !== "text") {
      //base page functionality only support text so if you want to use another type override this method
      return false;
    }
    $text = $message->getText();
    $button = $this->isInButtons($text);
    if ($button) {
      $button->execute();
      return true;
    }
    return false;
  }

  /**
   * @throws TelegramException
   */
  public function onPageOpen() {
    if (!$this->isObjectValidate()){
      MSG::messageForDebug("object ".$this->getClassName()."not validate for showing");
      return;
    }
    $this->initKeyboard(false);
    $messages = $this->getMessages();
    if ($messages === null || sizeof($messages) == 0) {
      return;
    }
    $responses = array();
    foreach ($messages as $message) {
      $responses[] = Request::sendMessage([
          "chat_id" => $this->getUpdate()->getMessage()->getFrom()->getId(),
          "text" => $message,
          'reply_to_message_id' => $this->getUpdate()->getMessage()->getMessageId(),
          "reply_markup" => $this->getKeyboard()
      ]);
    }
  }

  /**
   * if you just want to add btn on page and dont want to
   * execute them for better performance set $initOnClick = false
   * @param bool $initOnClick
   */
  protected function initButtons($initOnClick = true) {

    if ($this->getDirection()->canGoBack(false)) {
      $this->buttons[self::BT_BACK] = $this->createBackButton();
    }
    $onPageBtnClick = null;
    if ($initOnClick) {
      $onPageBtnClick = function (Button $btn) {
        $this->getDirection()->navigateTo($btn->getName());
      };
    }
    foreach ($this->forwardPages as $page) {
      $button = new Button(
          $page
          , $page::getName($this->getUserLanguage())
          , $onPageBtnClick !== null ? $onPageBtnClick : null
      );
      $this->buttons["$page"] = $button;
    }
  }

  protected function initKeyboard($alsoInitBtnAction = false) {
    $this->initButtons($alsoInitBtnAction);
//    MSG::messageForDebug(print_r($this->buttons,true));
    $this->styleKeyboard();
  }
  public function isObjectValidate(){
    return true;
  }

  /**
   * @return Update
   */
  public function getUpdate() {
    return $this->update;
  }

  /**
   * @return Direction
   */
  protected function getDirection() {
    return $this->direction;
  }

  public function clearInputs() {
    foreach ($this->inputs as $key => $value) {
      $this->inputs[$key] = null;
    };
    $this->getFirstNullInput();
    DBOperation::updateDirection($this->getUpdate()->getMessage()->getFrom()->getId(), $this->getDirection());
  }

  protected function setKeyboardAsNew() {
    $this->keyboard = new Keyboard([]);
    $this->keyboard->setResizeKeyboard(true);
  }

  /**
   * @return Keyboard
   */
  public function getKeyboard() {
    if ($this->keyboard === null) {
      $this->setKeyboardAsNew();
    }
    return $this->keyboard;
  }


  /**
   * @return array
   */
  public
  function getInputs(): array {
    return $this->inputs;
  }

  /**
   * show this array as separated message to user
   * @return array|null
   */
  public function getMessages() {
    return null;
  }


  public abstract function getFilePath();

  public abstract static function getName(Language $languageObject);

  public function clickOnButton($bt_name) {
    $this->initButtons();
    $button = $this->isInButtons($bt_name);
    if ($button) {
      $button->execute();
      return true;
    }
    MSG::messageForDebug("Is not in buttons $bt_name");
    return false;
  }


  protected function allInputsEntered() {
  }


  public abstract function getNameSpace();

  public abstract function getClassName();

  protected function translateToLanguage(string $inClassText) {
    return $inClassText;
  }

  public function isInButtons($text) {
    foreach ($this->getButtons() as $button) {
      if ($button->getName() == $text || $button->getDisplayName() == $text) {
        return $button;
      };
    }
    return false;
  }

  public function addInput($fieldName, $fieldValue) {
    if ($fieldValue === null || $fieldValue === null) return;
    if (key_exists($fieldName, $this->inputs)) {
      $this->inputs[$fieldName] = $fieldValue;
    }
    if ($name = $this->weRequireInput() != false) {
      $this->nextExpectedInput = $name;
    } else {
      $this->nextExpectedInput = null;
    }
  }

  public function addInputs(array $data) {
    foreach ($data as $name => $value) {
      $this->addInput($name, $value);
    }
  }

  protected function weRequireInput() {
    if ($this->nextExpectedInput !== null && key_exists((string)$this->nextExpectedInput, $this->inputs)) {
      return $this->nextExpectedInput;
    }
    return $this->getFirstNullInput();
  }

  protected function getFirstNullInput() {
    foreach ($this->inputs as $key => $value) {
      if ($value === null) return $key;
    }
    return null;
  }

  protected function isBackButton(Button $button) {
    return $button->getName() == $this->getUserLanguage()->kb_back();
  }

  protected function askRequiredInputsIfNeeded() {
    $weRequireInput = $this->weRequireInput();
    if (!$weRequireInput) return;
    $this->replyToUser(
        $this->getUserLanguage()->now_enter($this->translateToLanguage($weRequireInput))
        , $this->getKeyboard()
    );
  }

  protected function replyToUser($msg, $keyboard = null) {
    return Request::sendMessage([
        "chat_id" => $this->getUpdate()->getMessage()->getFrom()->getId(),
        "text" => $msg,
        "reply_to_message_id" => $this->getUpdate()->getMessage()->getMessageId(),
        "reply_markup" => $keyboard === null ? $this->getKeyboard() : $keyboard
    ]);
  }

  protected function createBackButton() {
    return new Button(self::BT_BACK, $this->getUserLanguage()->kb_back(), function ($btn) {
      $this->getDirection()->goBack(false);
    });
  }

  protected function styleKeyboard() {
    $btnNames = array();
    $back = null;
    if (isset($this->buttons[self::BT_BACK])) {
      $back = $this->buttons[self::BT_BACK];
      unset($this->buttons[self::BT_BACK]);
    }
    foreach ($this->getButtons() as $button) {
      $btnNames[] = $button->getDisplayName();
    }
    $chunk = array_chunk($btnNames, $this->MAX_COLUMN_SIZE);
    foreach ($chunk as $row) {
      $this->getKeyboard()->addRow(...$row);
    }
    //move back to end of list
    if ($back != null) {
      $this->getKeyboard()->addRow($back->getDisplayName());
    }
  }

  protected function getUserLanguage() {
    return Language::getINSTANCE(
        $this->getAccount()->language_code,
        $this->getAccount()->t_language_code
    );
  }

  protected function isAccountAdmin() {
    return (int)$this->getAccount()->getIsAdmin();
  }

  public function getForwardPages() {
    return $this->forwardPages;
  }

  public function getButtons() {
    return $this->buttons;
  }

  /**
   * @return Account
   */
  public function getAccount() {
    return $this->account;
  }

  protected function isAccountAdminFor(int $task) {
    return $this->getAccount()->getIsAdmin() & $task;
  }


}