<?php

namespace VoiceTroll\Commands;

use framework\util\MSG;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use VoiceTroll\locale\Language;
use VoiceTroll\Object\Bundle;

abstract class CMD {
  public static $isEnabled = true;

  public abstract static function getCommandName(): string;

  /**
   * @param Language $lang
   * @return string
   */
  public abstract static function getName(Language $lang): string;

  /**
   * @param Language $lang
   * @return string
   */
  public abstract static function getUsage(Language $lang): string;

  /**
   * @param Language $lang
   * @return string
   */
  public abstract static function getDescription(Language $lang): string;

  /**
   * @return bool
   */
  public abstract static function shouldShow(): bool;

  /**
   * @return bool
   */
  public abstract function shouldWorkForThisUser(): bool;

  /**
   * @var Bundle $update ;
   */
  private $bundle;

  /**
   * @return Bundle \Bundle
   */
  public function getBundle() {
    return $this->bundle;
  }


  public function __construct(Bundle $bundle) {
    $this->bundle = $bundle;
  }

  public abstract function execute();

  /**
   * Remove the keyboard and output a text
   *
   * @param string $text
   *
   * @return \Longman\TelegramBot\Entities\ServerResponse
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  protected function removeKeyboard($text) {
    return Request::sendMessage([
        'reply_markup' => Keyboard::remove(['selective' => true]),
        'chat_id' => $this->getBundle()->update->getMessage()->getChat()->getId(),
        'text' => $text,
    ]);
  }

  protected function replyToHandler(\Longman\TelegramBot\Entities\ServerResponse $response) {
    if (!$response->isOk()) {
      MSG::messageForDebug($response->getDescription());
    }
    return $response->isOk();
  }
}