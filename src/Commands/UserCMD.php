<?php

namespace VoiceTroll\Commands;

use Longman\TelegramBot\Request;
use VoiceTroll\locale\Language;

abstract class UserCMD extends CMD {
  /**
   * @param $msg
   * @param $keyboard
   * @return \Longman\TelegramBot\Entities\ServerResponse
   * @throws \Longman\TelegramBot\Exception\TelegramException
   */
  function replyToChat($msg, $keyboard=null) {
    $data = [
        "chat_id" => $this->getBundle()->update->getMessage()->getFrom()->getId(),
        "text" => $msg,
        "reply_to_message_id" => $this->getBundle()->update->getMessage()->getMessageId(),
    ];
    if ($keyboard!=null){
      $data["reply_markup"] = $keyboard;
    }

    return Request::sendMessage($data);
  }

  /**
   * @return Language
   */
  protected function getUserLanguage(){
    return Language::getINSTANCE(
        $this->getBundle()->account->language_code,
        $this->getBundle()->account->t_language_code
    );
  }
}