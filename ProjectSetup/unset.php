<?php

namespace ProjectSetup;

// Load composer
use framework\util\MSG;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/autoload.php';
require_once __DIR__ . '/autoload.php';
// Add you bot's API key and name
$setupProject = new TelegramBotSetupProject(true);
$setupProject->instantiateScriptConfig();
try {
  // Create Telegram API object
  $telegram = new Telegram(
      $setupProject->bot_api_key,
      $setupProject->bot_username);
  // Delete webhook
  $result = $telegram->deleteWebhook();
  if ($result->isOk()) {
    MSG::messageForDebug($result->getDescription());
  }
} catch (TelegramException $e) {
//  echo $e->getMessage();
  MSG::messageForDebug($e->getTraceAsString());
}