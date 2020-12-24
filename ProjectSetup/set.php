<?php

namespace ProjectSetup;
// Load composer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/autoload.php';
require_once __DIR__ . '/autoload.php';

use framework\util\MSG;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

$incomming = "i-am-telegram";
$hook_url = 'https://voice-troll.amir-ab.ir/' . $incomming;
$setupProject = new TelegramBotSetupProject(true);
$setupProject->instantiateScriptConfig();
try {
  // Create Telegram API object
  $telegram = new Telegram(
      $setupProject->bot_api_key,
      $setupProject->bot_username
  );
  // Set webhook
  $result = $telegram->setWebhook($hook_url);
  if ($result->isOk()) {
    MSG::messageForDebug($result->getDescription());
  } else {
    MSG::messageForDebug($result->getDescription());
  }
} catch (TelegramException $e) {
//    echo $e->getMessage();
  MSG::messageForDebug($e->getMessage());
}
MSG::messageForDebug();
