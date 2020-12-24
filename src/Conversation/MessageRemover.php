<?php


namespace VoiceTroll\Conversation;


use framework\util\MSG;
use Longman\TelegramBot\Request;

class MessageRemover {
  public static function remove(array $receivers) {
      MSG::messageForDebug($receivers);
    foreach ($receivers as $chat_id=>$message_id) {
      MSG::messageForDebug($chat_id.":".$message_id);
      $deleteMessage = Request::deleteMessage([
          "chat_id" => $chat_id,
          "message_id" => $message_id
      ]);
      MSG::messageForDebug($deleteMessage);
    }
  }
}