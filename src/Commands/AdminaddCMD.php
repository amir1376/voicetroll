<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/19/2019
 * Time: 12:33 PM
 */

namespace VoiceTroll\Commands;


use VoiceTroll\locale\Language;
use VoiceTroll\model\DBOperation;
use VoiceTroll\Object\PRIVILEGES;

class AdminaddCMD extends UserCMD {

  public static function getCommandName(): string {
    return "adminadd";
  }

  public static function getName(Language $lang): string {
    return "Admin Add";
  }

  public static function getUsage(Language $lang): string {
    return "adding admins";
  }

  public static function getDescription(Language $lang): string {
    return "adding admins";
  }

  public static function shouldShow(): bool {
    return false;
  }

  public function shouldWorkForThisUser(): bool {
    return (int)$this->getBundle()->account->getIsAdmin() & PRIVILEGES::ADD_ADMIN;
  }

  public function execute() {
    $userId = $this->getBundle()->update->getMessage()->getText(true);
    $userId = trim($userId);
    $account = DBOperation::getAccount($userId);
    if ($account === null) {
      $this->replyToChat("User have not conversation with me!");
      return;
    }
    $res = DBOperation::setAccountAdmin($account, 1);
    if ($res) {
      $this->replyToChat("Success");
    } else {
      $this->replyToChat("Failure");
    }
  }
}