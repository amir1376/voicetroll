<?php

namespace VoiceTroll\direction\page;

use framework\util\MSG;
use Longman\TelegramBot\Entities\Message;
use VoiceTroll\Conversation\CallbackQuerySender;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;

class MainPage extends BasePage {

  protected $forwardPages = [
      SongUploaderPage::class,
//      AboutPage::class,
      SongListPage::class,
      SettingPage::class,
  ];

  protected $inputs = [
      "query" => null
  ];




  static function getName(Language $languageObject) {
    return $languageObject->main_menu();
  }

  public function onNewMessageReceived(Message $message) {
    if (parent::onNewMessageReceived($message)) return true;
    if ($message->getType() != "text") {
      return false;
    }
    $this->queryForSong($message);
    return true;
  }

  /**
   * @return array
   */
  function getMessages() {
    return [
        $this->getUserLanguage()->main_menu() . PHP_EOL . $this->getUserLanguage()->querySearchForSongs()
    ];
  }


  function getFilePath() {
    return __FILE__;
  }


  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return __CLASS__;
  }

  protected function translateToLanguage(string $inClassText) {
    return $this->getUserLanguage()->querySearchForSongs();
  }

  private function queryForSong(Message $message) {
    $songs = DBOperation::searchForSong($message->getText());
    $result = $this->replyToUser(
        $this->getUserLanguage()->xItemsFound(sizeof($songs)),
        CallbackQuerySender::createSongListInlineKeyboard($this->getAccount(), $songs)
    );
    MSG::messageForDebug($result);
  }
}