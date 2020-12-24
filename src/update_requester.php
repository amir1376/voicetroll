<?php

use framework\util\MSG;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use VoiceTroll\Handler\MainHandler;
use VoiceTroll\model\DBOperation;
use VoiceTroll\config\TelegramBotProject;

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/autoload.php';
require_once __DIR__ . '/autoload.php';
try {
  $project = new TelegramBotProject(true);
  $project->instantiateScriptConfig();
  $telegram = new Telegram($project->bot_api_key, $project->bot_username);
  $handler = new MainHandler($telegram);
  $response = Request::getUpdates(
      [
          'offset' => DBOperation::getLastUpdate() + 1,
          'limit' => 10,
          'timeout' => 100,
      ]
  );
  if ($response->isOk()) {
    /** @var Update $update */
    foreach ($response->getResult() as $update) {
      $handleUpdate = $handler->handle($update);
//      echo $handleUpdate;
      DBOperation::addUpdate($update->getUpdateId(), $update->getRawData());
      echo PHP_EOL;
    }
  } else {
    MSG::messageForDebug("failed");
    MSG::messageForDebug($response->getDescription());
  }
} catch (TelegramException $e) {
  MSG::messageForDebug($e->getMessage());
}