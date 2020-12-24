<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace VoiceTroll\Commands;

use framework\util\MSG;
use framework\util\StringUtils;
use Longman\TelegramBot\Request;
use VoiceTroll\locale\Language;

/**
 * User "/help" command
 *
 * CMD that lists all available commands and displays them in User and Admin sections.
 */
class HelpCMD extends UserCMD {
  /**
   * @inheritdoc
   */
  public function execute() {
    $message = $this->getBundle()->update->getMessage();
    $chat_id = $message->getChat()->getId();
    $command_str = trim($message->getText(true));
    $data = [
        'chat_id' => $chat_id,
        'parse_mode' => 'HTML',
    ];
    $commands = $this->getAllAvailableCommands();
    //todo All <, > and & symbols that are not a part of a tag or an HTML entity must be replaced with the corresponding HTML entities (< with &lt;, > with &gt; and & with &amp;
    // If no command parameter is passed, show the list.
    if ($command_str === '') {
      $data['text'] = '*Commands List*:' . PHP_EOL;
      foreach ($commands as $commandName => $commandObject) {
        /** @var CMD $commandObject */
        $data['text'] .= '/' . $commandName . ' - ' . $commandObject::getName($this->getUserLanguage()) . PHP_EOL;
      }
      $data['text'] .= PHP_EOL . $this->getUserLanguage()->exactHelpType("/{$this::getCommandName()}");
      print_r($data);
      return $this->replyToHandler(
          Request::sendMessage($data)
      );
    }
    $command_str = str_replace('/', '', $command_str);
    if (isset($commands[$command_str])) {
      /** @var CMD $commandObject */
      $commandObject = $commands[$command_str];
      $data['text'] = sprintf(
          'CMD: %s' . PHP_EOL .
          'Description: %s' . PHP_EOL .
          'Usage: %s',
          $commandObject::getCommandName(),
          $commandObject::getDescription($this->getUserLanguage()),
          $commandObject::getUsage($this->getUserLanguage())
      );

      return $this->replyToHandler(
          Request::sendMessage($data)
      );
    }

    $data['text'] = 'No help available: CMD /' . $command_str . ' not found';

    return $this->replyToHandler(
        Request::sendMessage($data)
    );
  }

  protected function getAllAvailableCommands() {
    $dirname = dirname(__FILE__);
    $iterator = new \RecursiveDirectoryIterator($dirname);
    $commands = array();
    foreach ($iterator as $file) {
      MSG::messageForDebug($file);
      if (StringUtils::string_ends_with($file, "CMD.php")) {
        $classFileName = basename($file);
        /** @var CMD $class */
        $class = __NAMESPACE__ . "\\" . substr($classFileName, 0, strlen($classFileName) - strlen(".php"));
        MSG::messageForDebug($class);
        if (!class_exists($class)) {
          continue;
        }
        if (!$class::$isEnabled) {
          continue;
        }
        if (!(new \ReflectionClass($class))->isInstantiable()) {
          continue;
        }
        $cmd = new $class($this->getBundle());
        /** @var CMD $cmd */
        if (!$cmd->shouldWorkForThisUser()) {
          continue;
        }
        $commands[$class::getCommandName()] = $cmd;
      }
    }
    return $commands;
  }

  /**
   * @inheritdoc
   */

  public static function shouldShow(): bool {
    return true;
  }

  /**
   * @inheritdoc
   */
  public static function getCommandName(): string {
    return "help";
  }

  /**
   * @inheritdoc
   */
  public function shouldWorkForThisUser(): bool {
    return true;
  }

  /**
   * @inheritdoc
   */
  public static function getDescription(Language $lang): string {
    return $lang->help_description();
  }

  /**
   * @inheritdoc
   */
  public static function getName(Language $lang): string {
    return $lang->help_name();
  }

  /**
   * @inheritdoc
   */
  public static function getUsage(Language $lang): string {
    return $lang->help_usage();
  }


}
