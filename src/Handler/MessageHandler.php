<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 4:28 AM
 */

namespace VoiceTroll\Handler;


use framework\util\MSG;
use framework\util\StringUtils;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use VoiceTroll\Commands\CMD;
use VoiceTroll\Object\Bundle;

class MessageHandler extends Handler {

  /**
   * @param Bundle $bundle
   * @return bool
   */
  public function handle($bundle) {
    $account = $bundle->account;
    /** @var boolean $handled */
    $handled = false;
    $bundle->UpdateProcessor->addCommandsClasspath(
        $this->getDefaultCommandClassPath()
    );
    if (($name = $this->isCommand($bundle->update))) {
      MSG::messageForDebug("is command?....");
      $handled = $this->handleCommand($bundle, $name);
    } else {
      MSG::messageForDebug("is not command....");
      $handled = $this->handleNotCommands($bundle);
    }
    if (!$handled) {
      $this->handleFailure($bundle);
    }
    $bundle->direction->applyToDatabase();
    unset($bundle);
    return $handled;
  }
  /**
   * @param Bundle $bundle
   * @param string $name
   * @return bool
   */
  private function handleCommand(Bundle $bundle, string $name): bool {
    $objectName = $this->getCommandNameObject($bundle, $name);
    if ($objectName === null) return false;
    /** @var CMD $command */
    $command = new $objectName($bundle);
//    print_r($command);
//    exit;
    if (!$command->shouldWorkForThisUser()) {
      return false;
    }
    return $command->execute();
  }
  private function getCommandNameObject(Bundle $bundle, string $name) {
    $classname = self::convertToClassName($name);
    if ($classname === null || $classname === "") return null;
    foreach ($bundle->UpdateProcessor->getCommandsClasspaths() as $namespace) {
      $commandClassName = $namespace . "\\" . $classname . "CMD";
      //      if (is_file())
      if (!class_exists($commandClassName)) return null;
      /** @var CMD $commandClassName */
      if ($commandClassName::$isEnabled) {
        return $commandClassName;
      }
    }
    return null;
  }
  private function handleNotCommands(Bundle $bundle): bool {

    $headPageWithParams = $bundle->direction->getHeadPageWithParams();
    $handleOnNewDataReceived = $headPageWithParams->onNewMessageReceived($bundle->update->getMessage());
    return $handleOnNewDataReceived;
  }

  public function isCommand(Update $update) {
    $updateType = $update->getUpdateType();
    $commandName = null;

    //something went wrong
    if ($updateType === null) return false;

    if ($updateType == "message") {
      $commandName = $update->getMessage()->getCommand();
      if ($commandName === null) {
        //we have simple message
        return false;
      }
    } else {
      $commandName = $updateType;
    }
    return $commandName === null ? false : $commandName;
  }

  public function handleFailure(Bundle $bundle) {
    Request::sendMessage([
        "chat_id" => $bundle->update->getMessage()->getFrom()->getId(),
        "text" => $this->getUserLanguage($bundle)->NoCommandFoundUseHelp(""),
        'reply_to_message_id' => $bundle->update->getMessage()->getMessageId(),
    ]);
  }
  private static function convertToClassName(string $name): string {
    if ($name === null) return null;
    $out = array();
    $chArray = str_split($name);
    $length = sizeof($chArray);
    $nextMustBeUpper = true;
    for ($i = 0; $i < $length; $i++) {
      if ($chArray[$i] == "_") {
        $nextMustBeUpper = true;
        continue;
      }
      if ($nextMustBeUpper) {
        $out[] = strtoupper($chArray[$i]);
        $nextMustBeUpper = false;
        continue;
      }
      $out[] = $chArray[$i];
    }
    return StringUtils::arrayToString($out);
  }

  private function commandAllowed(string $name) {
    return true;
  }
  private function getDefaultCommandClassPath() {
    return "VoiceTroll\\Commands";
  }
}