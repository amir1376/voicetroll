<?php

namespace VoiceTroll\direction\page;

use framework\util\MSG;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use VoiceTroll\model\FSystemOperation;
use VoiceTroll\Object\Button;

class LanguageSelectorPage extends BasePage {

  public function getMessages() {
    return null;
  }

  public function onPageOpen() {
    parent::onPageOpen();
//    $this->addLanguagesToKeyBoard();
    MSG::messageForDebug("SELECT LANG MUST BE SHOWN");
    $response = $this->replyToUser(
        $this->getUserLanguage()->select_language()
        , $this->getKeyboard()
    );
    if (!$response->isOk()) {
      MSG::messageForDebug($response->getDescription());
    }
  }

  public function getFilePath() {
    return __FILE__;
  }

  protected function initButtons($initOnClick = true) {
    parent::initButtons($initOnClick);
    $lanSelectCallable = function (Button $button) {
      $code = FSystemOperation::haveThisLanguage($button->getDisplayName(), false);
      if ($code) {
//              MSG::messageForDebug("We have this lang $button->getDisplayName()");
        $this->getAccount()->language_code = $code;
        DBOperation::selectUserLanguage($this->getAccount()->s_id, $code);
        $this->getDirection()->ignoreMyDirectionAndProceedFromBack();
        return true;
      } else {
        MSG::messageForDebug("We don't have this lang {$button->getDisplayName()}");
        return false;
      }
    };
    $languageNames = Language::getAvailableLanguageNames();
    foreach ($languageNames as $name) {
      $this->buttons["$name"] = new Button(
          $name,
          $name,
          $lanSelectCallable
      );
    }
  }

  public static function getName(Language $languageObject) {
    return $languageObject->select_language();
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