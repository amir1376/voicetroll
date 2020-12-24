<?php


namespace VoiceTroll\direction\page;

use framework\util\MSG;
use framework\util\StringUtils;
use Longman\TelegramBot\Entities\Message;
use VoiceTroll\Conversation\CallbackQuerySender;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;

class SongUploaderPage extends BasePage {

  /**
   * @return array
   */
  protected $inputs = [
      "song" => null,
      "title" => null,
      "emoji" => null,
  ];

  public function getMessages() {
    return [];
  }

  public function onPageOpen() {
    parent::onPageOpen();
    $this->replyToUser(
        $this->getUserLanguage()->pleaseSendMeSong()
    );
  }

  public function onNewMessageReceived(Message $message) {
    if (parent::onNewMessageReceived($message)) return true;
    $type = $message->getType();
    MSG::messageForDebug("$type");
    if ($this->inputs["song"] === null) {
      $this->handleIncomingSong();
      $this->askRequiredInputsIfNeeded();
      return true;
    }
    if ($this->inputs["title"] === null) {
      $this->handleIncomingTitle();
      $this->askRequiredInputsIfNeeded();
      return true;
    }
    if ($this->inputs["emoji"] === null) {
      $this->handleIncomingEmoji();
    }
    $userForm = array(
        "t_file_id" => $this->inputs["song"]["t_file_id"],
        "t_id" => $message->getFrom()->getId(),
        "title" => $this->inputs["title"],
        "emoji" => $this->inputs["emoji"],
        "msg_type" => $this->inputs["song"]["msg_type"],
        "duration" => $this->inputs["song"]["duration"],
        "mime_type" => $this->inputs["song"]["mime_type"],
    );
    DBOperation::addSongs(
        $userForm["t_file_id"],
        $userForm["t_id"],
        $userForm["title"],
        $userForm["emoji"],
        $userForm["msg_type"],
        $userForm["duration"],
        $userForm["mime_type"]
    );
    $this->notifyAdminsThatWeHaveNewSong($userForm);
    $this->replyToUser($this->getUserLanguage()->song_received_and_checked());
    $this->getDirection()->ignoreMyDirectionAndProceedFromBack();
    return true;
  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return $languageObject->uploadSong();
  }

  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return __CLASS__;
  }

  protected function translateToLanguage(string $inClassText) {
    switch ($inClassText) {
      case "song":
        return $this->getUserLanguage()->song();
      case "title":
        return $this->getUserLanguage()->title();
      case "emoji":
        return $this->getUserLanguage()->emoji();
    }
    return $inClassText;
  }

  private function handleIncomingSong() {
    $message = $this->getUpdate()->getMessage();
    $type = $message->getType();
    switch ($type) {
      case "voice":
        $song = $message->getVoice();
        $this->addInput("song", [
            "msg_type" => $type,
            "t_file_id" => $song->getFileId(),
            "duration" => $song->getDuration(),
            "mime_type" => $song->getMimeType(),
        ]);
        break;
      case "audio":
        $song = $message->getAudio();
        $this->addInput("song", [
            "msg_type" => $type,
            "t_file_id" => $song->getFileId(),
            "duration" => $song->getDuration(),
            "mime_type" => $song->getMimeType(),
        ]);
        break;
      default:
        $this->replyToUser($this->getUserLanguage()->enterSongFirst());
    }
  }

  private function handleIncomingTitle() {
    $message = $this->getUpdate()->getMessage();
    $type = $message->getType();
    if ($type == "text") {
      $this->addInput("title", $message->getText());
    } else {
      $this->replyToUser($this->getUserLanguage()->sendMeText());
    }
  }

  private function handleIncomingEmoji() {
    $message = $this->getUpdate()->getMessage();
    $type = $message->getType();
    if ($type == "text" && ($emoji = StringUtils::getFirstEmoji($message->getText()))) {
      $this->addInput("emoji", $emoji);
    } else {
      $this->replyToUser($this->getUserLanguage()->sendMeEmoji());
    }
  }

  private function notifyAdminsThatWeHaveNewSong($song) {
    CallbackQuerySender::showAdminsNewSong(
        $song["t_file_id"]
    );
  }
}