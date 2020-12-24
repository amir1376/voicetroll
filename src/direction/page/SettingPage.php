<?php
namespace VoiceTroll\direction\page;

use VoiceTroll\locale\Language;

class SettingPage extends BasePage {

  protected $forwardPages=[
      LanguageSelectorPage::class
  ];
  /**
   * @return array|null
   */
  public function getMessages() {
    return [
        $this->getUserLanguage()->settings()
    ];
  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return $languageObject->settings();
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