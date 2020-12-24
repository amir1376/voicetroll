<?php
namespace VoiceTroll\Object;

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use VoiceTroll\direction\Direction;

class Bundle {
  /**
   * @var Telegram $telegram
   */

  public $telegram;
  /**
   * @var Update $update
   */
  public $update;
  /**
   * @var UpdateProcessor $UpdateProcessor
   */
  public $UpdateProcessor;
  /**
   * @var Direction $direction
   */
  public $direction;
  /**
   * @var Account
   */
  public $account;

}