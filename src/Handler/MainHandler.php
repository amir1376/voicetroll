<?php

namespace VoiceTroll\Handler;


use framework\util\MSG;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Telegram;
use RuntimeException;
use VoiceTroll\Object\Account;
use VoiceTroll\direction\Direction;
use VoiceTroll\Handler\CallbackQueryReceiverHandler;
use VoiceTroll\Handler\Handler;
use VoiceTroll\Handler\InlineQueryHandler;
use VoiceTroll\Handler\MessageHandler;
use VoiceTroll\Handler\QueryChosenHandler;
use VoiceTroll\Object\Bundle;
use VoiceTroll\Object\UpdateProcessor;

class MainHandler extends Handler {
  public $telegram;

  public function __construct(Telegram $telegram) {
    $this->telegram = $telegram;
  }

  protected function createBundle(Update $update, User $user) {
    $account = Account::createAnAccountIfNotExistsAndReturnAccount($update, $user);
    if ($account == false) throw new RuntimeException("We have mistake in database ");
    $bundle = new Bundle();
    $bundle->direction = new Direction($update, $account, ($account->direction));
    $bundle->account = $account;
    $bundle->telegram = $this->telegram;
    $bundle->update = $update;
    $bundle->UpdateProcessor = new UpdateProcessor();
    return $bundle;
  }

  /**
   * @param Update $update
   * @return bool
   */
  public function handle($update) {
    $updateType = $update->getUpdateType();
    switch ($updateType) {
      case "message":
        $message = $update->getMessage();
        $bundle = $this->createBundle($update, $message->getFrom());
        return $this->handleMessageUpdate($bundle);
      case "inline_query":
        $inlineQuery = $update->getInlineQuery();
        $bundle = $this->createBundle($update, $inlineQuery->getFrom());
        return $this->handleInlineQuery($bundle);
      case "chosen_inline_result":
        $chosenInlineResult = $update->getChosenInlineResult();
        $bundle = $this->createBundle($update, $chosenInlineResult->getFrom());
        return $this->handleQueryChosen($bundle);
      case "callback_query":
        $callbackQuery = $update->getCallbackQuery();
        $bundle = $this->createBundle($update, $callbackQuery->getFrom());
        return $this->handleCallbackQuery($bundle);
      default:
        {
          MSG::messageForDebug("Type is : $updateType is not handled");
          return false;
        }
    }
  }

  private function handleMessageUpdate(Bundle $bundle) {
    return (new MessageHandler())->handle($bundle);
  }

  private function handleInlineQuery(Bundle $bundle): bool {
    return (new InlineQueryHandler())->handle($bundle);
  }

  private function handleQueryChosen(Bundle $bundle) {
    return (new QueryChosenHandler())->handle($bundle);
  }

  private function handleCallbackQuery(Bundle $bundle) {
    return (new CallbackQueryReceiverHandler())->handle($bundle);
  }

}