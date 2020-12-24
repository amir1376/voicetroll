<?php

namespace VoiceTroll\Commands;

use VoiceTroll\locale\Language;

class WhereamiCMD extends UserCMD {

  public function execute() {
    $basePage = $this->getBundle()->direction->getHeadPageWithParams();
    $keyboard = $basePage?$basePage->getKeyboard():null;
    $msg = implode("|", $this->getBundle()->direction->getPages());
    if ($msg==null)$msg="yuu still no where";
    $serverResponse = $this->replyToChat(
        $msg
        , $keyboard
    );
    return true;
  }

  public static function getName(Language$lang): string {
    return $lang->whereami_name();
  }

  public static function getUsage(Language$lang): string {
    return $lang->whereami_usage();
  }

  public static function shouldShow(): bool {
    return true;
  }

  public static function getCommandName(): string {
    return "whereami";
  }

  public static function getDescription(Language $lang): string {
    return $lang->whereami_description();
  }

  public function shouldWorkForThisUser(): bool {
    return true;
  }
}