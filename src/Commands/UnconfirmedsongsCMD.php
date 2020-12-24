<?php

namespace VoiceTroll\Commands;

use VoiceTroll\direction\page\SongListPage;
use VoiceTroll\locale\Language;
use VoiceTroll\Object\PRIVILEGES;

class UnconfirmedsongsCMD extends UserCMD {

  public function execute() {
    $language = $this->getUserLanguage();
    $newPage = new SongListPage(
        $this->getBundle()->direction,
        $this->getBundle()->update,
        $this->getBundle()->account
    );
    $this->replyToChat(
        $language->redirectingTo($newPage::getName(
            $this->getUserLanguage()
        ))
    );
    $newPage->clickOnButton(SongListPage::BT_WAIT_FOR_CONFIRM);
    return true;
  }

  public static function getName(Language $lang): string {
    return $lang->whereami_name();
  }

  public static function getUsage(Language $lang): string {
    return "to review users submitted songs";
  }

  public static function shouldShow(): bool {
    return true;
  }

  public static function getCommandName(): string {
    return "unconfirmed_songs";
  }

  public static function getDescription(Language $lang): string {
    return "list of unconfirmed songs";
  }

  public function shouldWorkForThisUser(): bool {
    return $this->getBundle()->account->getIsAdmin() & PRIVILEGES::ACCEPT_SONG;
  }
}