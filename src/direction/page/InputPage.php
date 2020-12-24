<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 6:42 AM
 */

namespace VoiceTroll\direction\page;


use Longman\TelegramBot\Entities\Message;
use VoiceTroll\Object\InputReceiver;

abstract class InputPage extends BasePage {

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
}