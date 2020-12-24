<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/20/2019
 * Time: 2:42 AM
 */

namespace VoiceTroll\direction\page;


use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use VoiceTroll\model\table\DBSong;
use VoiceTroll\Object\Button;
use VoiceTroll\Object\InputReceiver;

class SongDetailEditorPage extends BasePage implements InputReceiver {

  const BT_CH_TITLE = "bt_change_title";
  const BT_CH_EMOJI = "bt_change_emoji";
  const BT_CH_ACTIVE = "bt_change_is_active";
  const BT_APPLY = "bt_apply";


  protected $inputs = [
      DBSong::id => null,
      DBSong::title => null,
      DBSong::emoji => null,
      DBSong::is_active => null,
  ];
  private $song=null;

  public function setSongId(int $id){
    $this->inputs[DBSong::id]=$id;
  }
  public function getSongId(){
    return $this->inputs[DBSong::id];
  }
  public function isObjectValidate(){
    return $this->getSongId()!==null;
  }
  public function onPageOpen() {
    $this->sendSongDetailsToUser();
  }

  protected function initButtons($initOnClick = true) {
    parent::initButtons($initOnClick);
    $this->buttons[self::BT_CH_EMOJI] = new Button(
        self::BT_CH_EMOJI,
        "Change emoji",
        $this->createExecutableForPlainTextInput(
            $this->inputs[DBSong::emoji],
            $this->translateToLanguage($this->inputs[DBSong::emoji])
        )
    );
    $this->buttons[self::BT_CH_TITLE] = new Button(
        self::BT_CH_TITLE,
        "Change title",
        $this->createExecutableForPlainTextInput(
            $this->inputs[DBSong::title],
            $this->translateToLanguage($this->inputs[DBSong::title])
        )
    );
    $this->buttons[self::BT_CH_TITLE] = new Button(
        self::BT_CH_TITLE,
        "Change title",
        $this->createExectableForEnumInput(
            $this->translateToLanguage($this->inputs[DBSong::title]),
            $this->inputs[DBSong::title],
            [
                "✅" => 1,
                "❌" => -1
            ]
        )
    );
    $this->buttons[self::BT_APPLY] = new Button(
        self::BT_APPLY,
        "Apply",
        function (Button $button) {
          $copyInputs = $this->inputs;
          unset($copyInputs[DBSong::id]);
          $dbNewData = array();
          foreach ($copyInputs as $key => $value) {
            if ($value !== null) {
              $dbNewData[$key] = $value;
            }
          }
          DBOperation::updateSong($this->inputs[DBSong::id], $dbNewData);
        }
    );
  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return "SongDetailEditor";
  }

  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return self::class;
  }

  protected function translateToLanguage(string $inClassText) {
    return $inClassText;
  }

  private function createExecutableForPlainTextInput($key, $displayName) {
    return function (Button $button) use ($key, $displayName) {
      $textInputPage = new TextInputPage(
          $this->getDirection(),
          $this->getUpdate(),
          $this->getAccount()
      );
      $textInputPage->key = $key;
      $textInputPage->displayKey = $displayName;
    };
  }

  private function createExectableForEnumInput($key, $displayName, array $enums) {
    return function (Button $button) use ($key, $displayName, $enums) {
      $textInputPage = new KeyboardEnumInputPage(
          $this->getDirection(),
          $this->getUpdate(),
          $this->getAccount()
      );
      $textInputPage->key = $key;
      $textInputPage->displayKey = $displayName;
      $textInputPage->enums = $enums;
    };
  }

  public function onInputReceived($key, $value) {
    if (key_exists($key, $this->inputs)) {
      $this->addInput($key, $value);
    }

  }

  private function sendSongDetailsToUser() {
    $this->replyToUser($this->getSongDetails());
  }

  private function getSongDetails() {
//    $messageSendData = new MessageSendData();

    $language = $this->getUserLanguage();
    $caption="";
    $caption .= $language->titleIs($this->song["title"]);
    $caption .= $language->nextLine();
    $caption .= $language->emojiIs($this->song["emoji"]);
    $caption .= $language->nextLine();
//    return $messageSendData
    //todo
  }
}