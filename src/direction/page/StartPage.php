<?php


namespace VoiceTroll\direction\page;


use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use framework\util\MSG;

class StartPage extends BasePage {


  protected $inputs = [
  ];

  public function onPageOpen() {
    //TODO due more db operation  use from start bundle or inject with constructor
    $dbAccount = DBOperation::getAccountWithSettings($this->getUpdate()->getMessage()->getFrom()->getId());
    if (!isset($dbAccount["language_code"])) {
      MSG::messageForDebug("selecting lang");
      $this->getDirection()->navigateTo(LanguageSelectorPage::class);
      return;
    }
    $this->getDirection()->navigateTo(MainPage::class);
  }

  public function getMessages() {
    return [];
  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return $languageObject->startPage();
  }

  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return __CLASS__;
  }

  protected function translateToLanguage(string $inClassText) {
    return $inClassText;
  }
}