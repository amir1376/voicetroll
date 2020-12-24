<?php
namespace VoiceTroll\Commands;

use VoiceTroll\locale\Language;

/**
 * User "/cancel" command
 */
class CancelCMD extends CMD
{
    public function execute()
    {
      $this->getBundle()->direction->goBack(false,true);
    }

  public static function getName(Language$lang): string {
    return $lang->cancel_name();
  }

  public static function getUsage(Language$lang): string {
    return $lang->cancel_Usage();
  }

  public static function shouldShow(): bool {
    return true;
  }

  public static function getCommandName(): string {
    return "cancel";
  }

  public static function getDescription(Language $lang): string {
    return $lang->cancel_Description();
  }

  public function shouldWorkForThisUser(): bool {
    return true;
  }
}
