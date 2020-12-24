<?php
namespace VoiceTroll\direction\page;
use VoiceTroll\locale\Language;

class AboutPage extends BasePage {


  static function getName(Language $languageObject) {
    return $languageObject->aboutUs();
  }


  /**
   * @return array
   */
  function getMessages() {
    return [
        $this->getUserLanguage()->aboutUs()
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
    // TODO: Implement translateToLanguage() method.
  }
}