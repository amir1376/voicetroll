<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 2:04 AM
 */

namespace VoiceTroll\Handler;
use VoiceTroll\locale\Language;
use VoiceTroll\Object\Bundle;

abstract class Handler {
  public abstract function handle($bundle);

  protected function getUserLanguage(Bundle $bundle) {
    return Language::getINSTANCE(
        $bundle->account->language_code,
        $bundle->account->t_language_code
    );
  }
}