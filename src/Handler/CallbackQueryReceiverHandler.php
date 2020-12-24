<?php


namespace VoiceTroll\Handler;

use framework\util\MSG;
use VoiceTroll\Conversation\SongShower;
use VoiceTroll\direction\page\SongDetailEditorPage;
use VoiceTroll\model\DBOperation;
use VoiceTroll\model\table\DBSong;
use VoiceTroll\Object\Bundle;

class CallbackQueryReceiverHandler extends Handler {


  /**
   * @param Bundle $bundle
   * @return bool
   */
  public function handle($bundle) {
//    MSG::messageForDebug($callbackQuery);
    $callbackQuery=$bundle->update->getCallbackQuery();
    $messageId = $callbackQuery->getMessage()->getMessageId();
    $chatID = $callbackQuery->getMessage()->getChat()->getId();
    $data = $callbackQuery->getData();
    list($type, $query) = explode(":", $data, 2);
    MSG::messageForDebug("$type");
    if ($type === null) {
      return false;
    }
    switch ($type) {
      case "confirm_song":
        return self::onConfirmNewSong($query,$bundle);
      case "show_song":
        return self::handleShowSong($query,$bundle);
      case "edit_song":
        return self::handleEditSong($query,$bundle);
      default :
        MSG::messageForDebug("is not supported this calback :(" . $type . ")");
    }
    return false;
  }

  private static function onConfirmNewSong($query,Bundle $bundle) {
    list($file_id, $allowed) = explode(":", $query);
    $setSongAllowed = DBOperation::setSongAllowed($file_id, $allowed,$bundle->account->s_id);
    return true;
  }

  private static function handleShowSong($query,Bundle $bundle) {
    $song = DBOperation::getSongByTelegramFileId($query);
    if ($song==null){return false;}
    return SongShower::showSong(
        $song,
        $bundle->account->t_id,
        $bundle->account->language_code,
        $bundle->account->getIsAdmin()
    );
  }
  private static function handleEditSong($query, Bundle $bundle) {
    $song = DBOperation::getSongByTelegramFileId($query);
    $id = $song[DBSong::id];
    $class =new SongDetailEditorPage($bundle->direction,$bundle->update,$bundle->account);
    $class->setSongId($id);
    $bundle->direction->navigateTo($class);
  }
}