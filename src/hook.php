<?php
// Load composer
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use VoiceTroll\config\TelegramBotProject;
use VoiceTroll\Handler\MainHandler;

ini_set("display_errors",true);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../framework/autoload.php';
require_once __DIR__ . '/autoload.php';
$project = new TelegramBotProject(true);
$project->instantiateScriptConfig();
$input = file_get_contents('php://input');
$telegram = new Telegram($project->bot_api_key, $project->bot_username);
$handler = new MainHandler($telegram);
$json_decode = json_decode($input, true);
if (!is_array($json_decode)){
  \framework\util\MSG::messageForDebug("input failure!");
  return;
}
$handler->handle(new Update($json_decode, $project->bot_username));
