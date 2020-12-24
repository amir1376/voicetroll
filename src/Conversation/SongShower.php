<?php


namespace VoiceTroll\Conversation;


use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\InputMedia\InputMediaPhoto;
use Longman\TelegramBot\Entities\MessageEntity;
use Longman\TelegramBot\Entities\Voice;
use Longman\TelegramBot\Request;
use VoiceTroll\locale\Language;
use VoiceTroll\Object\PRIVILEGES;

class SongShower {
  private static function addActivationRow(
      &$song, Language $language, InlineKeyboard $inlineKeyboard
      , $activate = false, $deactivate = false
  ) {
    $row = array();
    if ($activate) {
      $row[] = new InlineKeyboardButton([
          "text" => $language->activate_now(),
          "callback_data" =>
              "confirm_song" . ":" .
              $song["t_file_id"] . ":" .
              "1"
      ]);
    }
    if ($deactivate) {
      $row[] = new InlineKeyboardButton([
          "text" => $language->deActivate_now(),
          "callback_data" =>
              "confirm_song" . ":" .
              $song["t_file_id"] . ":" .
              "0"
      ]);
    }
    $inlineKeyboard->addRow(...$row);
  }

  private static function addSongEditRow($song, Language $language, InlineKeyboard $inlineKeyboard) {
    $row = array();
    $row[] = new InlineKeyboardButton([
        "text" => $language->edit_song(),
        "callback_data" =>
            "edit_song" . ":" .
            $song["t_file_id"]
    ]);
    $inlineKeyboard->addRow(...$row);
  }

  public static function showSong($song, $chat_id, $lang, $is_admin = false, $header = null) {
    $is_admin = (int)$is_admin;
    $language = Language::getINSTANCE($lang);
    $caption = "";
    $action = "send" . ucfirst($song["msg_type"]);
    if ($is_admin) {
      $caption .= $language->ownerIdIs((int)$song["owner_id"]);
      $caption .= $language->nextLine();
      $confirmed_by = $song["confirmed_by"];
      if ($confirmed_by) {
        $caption .= $language->confirmedBy((int)$confirmed_by);
      }
      $caption .= $language->nextLine();
    }
    $caption .= $language->titleIs($song["title"]);
    $caption .= $language->nextLine();
    $caption .= $language->emojiIs($song["emoji"]);
    $caption .= $language->nextLine();
    $caption .= $language->xTimeUsed((int)$song["use_count"]);
    $data = array();
    $data["chat_id"] = $chat_id;
    $data[$song["msg_type"]] = $song["t_file_id"];
    if ($header != null) {
      $caption = $header
          . $language->nextLine()
          . $caption;
    }
    $data["caption"] = $caption;
    $inlineKeyboard = new InlineKeyboard([]);
    if ($is_admin & PRIVILEGES::EDIT_WHOLE_SONG) {
      self::addActivationRow($song, $language, $inlineKeyboard, true, true);
      self::addSongEditRow($song, $language, $inlineKeyboard);
    }
    $data["reply_markup"] = $inlineKeyboard;
    $data["parse_mode"] = "HTML";
    Request::send($action, $data);
    return true;
  }

  public static function _showSong($song, $chat_id, $lang, $is_admin = false, $header = null) {
    $photo = new InputMediaPhoto();
    Request::send("");

  }


}