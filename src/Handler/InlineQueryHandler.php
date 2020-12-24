<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 2:04 AM
 */

namespace VoiceTroll\Handler;


use framework\util\MSG;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultCachedAudio;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultCachedVoice;
use Longman\TelegramBot\Request;
use VoiceTroll\model\DBOperation;
use VoiceTroll\Object\Bundle;

class InlineQueryHandler extends Handler {
  /**
   * @param Bundle $bundle
   * @return bool
   */
  public function handle($bundle) {
//    MSG::messageForDebug("inline query is: " . "'" . $query . "'");
//    if (strlen($query) < 1) {
//      return true;
//    }
    $answerData = array();
    $update = $bundle->update;
    $inline_query = $update->getInlineQuery();
//    MSG::messageForDebug($inline_query);
//    $query = $inline_query->getQuery();
    $userId = $inline_query->getFrom()->getId();
    $answerData["results"] = array();
    $answerData["is_personal"] = true;
    if (!self::userHaveChat($userId)) {
      self::proceedForUserThatDontHaveChat($bundle, $answerData);
    } else {
      self::proceedForUserThatHaveChat($bundle, $answerData);
    }

    $answerData["cache_time"] = 0;
    $answerData["inline_query_id"] = $inline_query->getId();
    MSG::messageForDebug($answerData);
    $serverResponse = Request::answerInlineQuery($answerData);
//    $serverResponse = $inline_query->answer($data);
    if ($serverResponse->isOk()) {

      MSG::messageForDebug("OK ! ");
    } else {
//      MSG::messageForDebug($serverResponse->getDescription()));
      MSG::messageForDebug($serverResponse);
    }
    return true;
  }

  private static function createListArray($songList, &$results) {
    foreach ($songList as $song) {
      /** @var InlineQueryResultCachedAudio|InlineQueryResultCachedVoice $result */
      $result = null;
      switch ($song["msg_type"]) {
        case "audio":
          $result = new InlineQueryResultCachedAudio();
          $result->setAudioFileId($song["t_file_id"]);
          break;
        case "voice":
          $result = new InlineQueryResultCachedVoice();
          $result->setVoiceFileId($song["t_file_id"]);
          break;
      }
      $result->setId($song["id"]);
      $result->setTitle($song["emoji"] . " " . $song["title"]);
//      $result->setCaption($song["emoji"]);
      $results[] = $result;
    }
  }

  private function proceedForUserThatDontHaveChat(Bundle $bundle, &$answerData) {
    $limitation = 1;
    $searchForSong = DBOperation::searchForSongToShowInList(
        $bundle->update->getInlineQuery()->getQuery(), $bundle->account->t_id, $limitation, 0
    );
//    $answerData["switch_pm_text"] ="Login First";
    $answerData["switch_pm_parameter"] = "new";
    $answerData["switch_pm_text"] = $this->getUserLanguage($bundle)
        ->please_start_bot_for_more_result();
    self::createListArray($searchForSong, $answerData["results"]);

  }


  private static function proceedForUserThatHaveChat(Bundle $bundle, &$answerData) {
    $limitation = 5;

    $inlineQuery = $bundle->update->getInlineQuery();
    $offset = $inlineQuery->getOffset();
    if ($offset == null) {
      $offset = 0;
    }
    $searchForSong = DBOperation::searchForSongToShowInList(
        $inlineQuery->getQuery(), $bundle->account->t_id, 10, $offset
    );
    $answerData["next_offset"] = $offset + $limitation;
    self::createListArray($searchForSong, $answerData["results"]);
  }

  private static function userHaveChat($id) {
    $chat = DBOperation::getChat($id);
    return $chat !== null;
  }
}