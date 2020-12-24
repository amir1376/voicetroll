<?php

namespace VoiceTroll\direction\page;

use VoiceTroll\Conversation\CallbackQuerySender;
use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use VoiceTroll\Object\Button;
use VoiceTroll\Object\PRIVILEGES;

class SongListPage extends BasePage {

  const BT_NEWEST = "bt_newest";
  const BT_REJECTED = "bt_rejected";
  const BT_WAIT_FOR_CONFIRM = "bt_wait_for_confirm";
  const BT_POPULAR = "bt_most_used";
  const MAX_NEWEST_LIMIT = 20;
  const MAX_POPULAR_LIMIT = 20;
  const MAX_REJECTED_LIMIT = 20;
  const MAX_UNCONFIRMED_LIMIT = 20;
  protected $forwardPages = [
  ];


  /**
   * @return array|null
   */
  public function getMessages() {
    return [
        $this->getUserLanguage()->msg_select()
    ];
  }

  function createOnClickForShowList($title, $songs) {
    return function (Button $button) use ($title, $songs) {
      return $this->replyToUser(
          $title,
          CallbackQuerySender::createSongListInlineKeyboard($this->getAccount(), $songs)
      );
    };
  }

  protected function initButtons($initOnClick = true) {
    parent::initButtons($initOnClick);

    $this->buttons[self::BT_NEWEST] = new Button(
        self::BT_NEWEST,
        $this->getUserLanguage()->newest(),
        $this->createOnClickForShowList(
            $this->getUserLanguage()->newest(),
            DBOperation::searchForNewestSongs(self::MAX_NEWEST_LIMIT)
        )
    );
    $this->buttons[self::BT_POPULAR] = new Button(
        self::BT_NEWEST,
        $this->getUserLanguage()->popular(),
        $this->createOnClickForShowList(
            $this->getUserLanguage()->popular(),
            DBOperation::searchForPopularSongs(self::MAX_POPULAR_LIMIT)
        )
    );
    //end of common buttons..................
//    $admin = $this->account->is_admin;
    if ($this->isAccountAdminFor(PRIVILEGES::ACCEPT_SONG)) {
      $this->buttons[self::BT_WAIT_FOR_CONFIRM] = new Button(
          self::BT_WAIT_FOR_CONFIRM,
          $this->getUserLanguage()->unconfirmed(),
          $this->createOnClickForShowList(
              $this->getUserLanguage()->unconfirmed(),
              DBOperation::searchForUnconfirmedSongs(self::MAX_UNCONFIRMED_LIMIT)
          )
      );
    }
    if ($this->isAccountAdminFor(PRIVILEGES::REJECT_SONG)) {
      $this->buttons[self::BT_REJECTED] = new Button(
          self::BT_REJECTED,
          $this->getUserLanguage()->rejected(),
          $this->createOnClickForShowList(
              $this->getUserLanguage()->rejected(),
              DBOperation::searchForRejectedSongs(self::MAX_REJECTED_LIMIT)
          )
      );
    }

  }

  public function getFilePath() {
    return __FILE__;
  }

  public static function getName(Language $languageObject) {
    return $languageObject->songList();
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