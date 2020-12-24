<?php
namespace VoiceTroll\Conversation;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;
use VoiceTroll\model\DBOperation;

class BroadcastMSG {

//TODO fix this

  public static function broadcast(Message $data, $condition) {
    $users = DBOperation::getAllAccountWithSettings();
    $action = "send" . ucfirst($data->getType());
    $sendData = [
        $data->getType() => $data["t_file_id"],
//        "reply_markup" => $inlineKeyboard
    ];
    $msgReceivers = array();
    foreach ($users as $user) {
      $sendData["chat_id"] = $user["s_id"];
      $sendData["parse_mode"] = "html";
      $response = Request::send($action, $sendData);
      if ($response->isOk()) {
        /** @var Message $result */
        $result = $response->getResult();
        $msgReceivers[] = [
            $result->getChat()->getId() => $result->getMessageId()
        ];
      }
    }
    DBOperation::registerRemovableMessage($data["t_file_id"], json_encode($msgReceivers));

  }
}
