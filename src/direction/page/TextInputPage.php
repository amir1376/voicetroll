<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 6:42 AM
 */

namespace VoiceTroll\direction\page;


use Longman\TelegramBot\Entities\Message;
use VoiceTroll\locale\Language;

class TextInputPage extends NonKeyboardBasedInputPage {

  /**
   * @return array|null
   */
  public $displayKey;
  public $key;
  public $value;

  public function getMessages() {
    return [
        $this->getUserLanguage()->now_enter($this->displayKey)
    ];
  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return $languageObject->textInputPage();
   }

  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return self::class;
  }

  protected function acceptable(Message $message): bool {
    return $message->getType() == "text";
  }

  protected function extractValue(Message $message) {
    return $message->getText();
  }
}