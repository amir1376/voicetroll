<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 12:11 PM
 */

namespace VoiceTroll\direction\page;


use Longman\TelegramBot\Entities\Message;
use VoiceTroll\Object\InputReceiver;

abstract class NonKeyboardBasedInputPage extends InputPage {

  public function onNewMessageReceived(Message $message) {
    $success = parent::onNewMessageReceived($message);
    if ($success) {
      return $success;
    }
    if (!$this->acceptable($message)) {
      return false;
    }
    $backPage = $this->getDirection()->getBackPage();
    if ($backPage instanceof InputReceiver) {
      $backPage->onInputReceived($this->key, $this->extractValue($message));
      return true;
    }
    return false;
  }


  protected abstract function acceptable(Message $message): bool;

  protected abstract function extractValue(Message $message);

}