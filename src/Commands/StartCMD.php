<?php

namespace VoiceTroll\Commands;

use framework\util\MSG;
use VoiceTroll\locale\Language;
use Longman\TelegramBot\Request;
use VoiceTroll\model\DBOperation;

class StartCMD extends UserCMD {
  /**
   * @var bool
   */
  protected static $private_only = true;

  public function execute() {
    $user = $this->getBundle()->update->getMessage()->getFrom();
    $chat = $this->getBundle()->update->getMessage()->getChat();
    $dbChat = DBOperation::chatExistForUser($chat->getId());
    if ($dbChat == null) {
      DBOperation::addChat(
          $chat->getId(),
          $user->getId(),
          $chat->getType()
      );
    }
    MSG::messageForDebug("chat created for " . $user->getId());
    $message = [
        "chat_id" => $user->getId(),
        "text" => $this->getUserLanguage()->msg_welcome(),
    ];
    Request::sendMessage($message);
    $this->getBundle()->direction->startAsNewDirection();
    $this->getBundle()->direction->getHeadPageWithParams()->onPageOpen();
    return true;
  }

  public static function getName(Language$lang): string {
    return $lang->start_name();
  }

  public static function getUsage(Language$lang): string {
    return $lang->start_usage();
  }

  public static function shouldShow(): bool {
    return true;
  }

  public static function getCommandName(): string {
    return "start";
  }

  public static function getDescription(Language $lang): string {
    return $lang->start_description();
  }

  public function shouldWorkForThisUser(): bool {
    return true;
  }
}