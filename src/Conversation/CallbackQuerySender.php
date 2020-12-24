<?php


namespace VoiceTroll\Conversation;


use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use VoiceTroll\Object\Account;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;

class CallbackQuerySender {

  public static function showAdminsNewSong($t_file_id) {
    $song = DBOperation::getSongByTelegramFileId($t_file_id);
    $admins = DBOperation::getAdmins();
    foreach ($admins as $admin) {
      SongShower::showSong($song,
          $admin["s_id"],
          $admin["language_code"],
          $admin["is_admin"],
          Language::getINSTANCE(
              $admin["language_code"],
              $admin["t_language_code"]
          )->newSongReceived()
      );
    }
//    DBOperation::registerRemovableMessage($t_file_id["t_file_id"], json_encode($msgReceivers));
  }

  public static function createSongListInlineKeyboard(Account $account, array $songs) {
    $inlineKeyboard = new InlineKeyboard([]);
    foreach ($songs as $song) {
      $inlineKeyboard->addRow(new InlineKeyboardButton([
          "text" => $song["emoji"] . " " . $song["title"],
          "callback_data" => "show_song" . ":"
              . $song["t_file_id"]
      ]));
    }
    return $inlineKeyboard;
  }

}