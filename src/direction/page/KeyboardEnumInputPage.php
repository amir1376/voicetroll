<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 11:48 AM
 */

namespace VoiceTroll\direction\page;


use Longman\TelegramBot\Entities\Message;
use VoiceTroll\locale\Language;
use VoiceTroll\Object\Button;
use VoiceTroll\Object\InputReceiver;

class KeyboardEnumInputPage extends KeyboardBasedInputPage {

  /**
   * name  as key => displayname as value
   * @var array $enums
  */
  public $enums=null;

  public static function getName(Language $languageObject) {
    return $languageObject->select_option_page();
  }

  protected function initButtons($initOnClick = true) {
    parent::initButtons($initOnClick);
    $fun =function (Button $button){
      $backPage = $this->getDirection()->getBackPage();
      if ($backPage instanceof InputReceiver) {
        $backPage->onInputReceived($this->key, $button->getName());
        return true;
      }
      return false;
    };
    foreach ($this->enums as $enumKey=>$displayName) {
      $this->buttons[$enumKey]=new Button($enumKey,$displayName,$fun);
    }
  }

  public function getNameSpace() {
    return __NAMESPACE__;
  }

  public function getClassName() {
    return self::class;
  }

}