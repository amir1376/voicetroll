<?php

namespace VoiceTroll\locale;

use framework\util\MSG;
use VoiceTroll\model\FSystemOperation;

abstract class Language {

  private static $defaultLang = "en";
  private static $INSTANCES = [];

  /**
   * @param string[] $possibleLangs
   * @return Language
   */
  public static function getINSTANCE(...$possibleLangs) {
    $selectedLang = null;
    foreach ($possibleLangs as $lang) {
      if (key_exists($lang, self::$INSTANCES)) {
        $selectedLang = $lang;

        break;
      } else {
        $className = self::convertToClassName($lang);
        if (class_exists($className)) {
          self::$INSTANCES[$lang] = new $className();
          $selectedLang = $lang;
          break;
        }
      }
    }
    if ($selectedLang === null) {
      $lang = self::$defaultLang;
      if (key_exists($lang, self::$INSTANCES)) {
        $selectedLang = $lang;
      } else {
        $lang = self::$defaultLang;
        $className = self::convertToClassName($lang);
        if (class_exists($className)) {
          self::$INSTANCES[$lang] = new $className();
          $selectedLang = $lang;
        }
      }
    }
    if ($selectedLang === null) {
      $lang = "en";
      self::$INSTANCES[$lang] = new LangEn();
      MSG::messageForDebug("passed languages not supported using English");
      $selectedLang = $lang;
    }
//    MSG::messageForDebug("$selectedLang");
    return self::$INSTANCES[$selectedLang];
  }


  public static function selectDefaultLanguage($lang) {
    self::$defaultLang = $lang;
  }

  public static function getLocaleDir() {
    return __DIR__;
  }

  public static function getLanguageCode(string $string) {
    $string = trim($string);
    $map = json_decode(file_get_contents(__DIR__ . "/language_code_map.json"), true);
    MSG::messageForDebug(gettype($map));
    foreach ($map as $code => $value) {
      if ($string === $value["name"]) return $code;
    }
    return null;
  }

  public static function getLanguageName(string $code) {
    $map = json_decode(file_get_contents(__DIR__ . "/language_code_map.json"), true);
    return $map[$code]["name"];
  }

  public static function getAvailableLanguageNames(): array {
    return FSystemOperation::getAllLanguageThatWeHave();
  }

  private static function convertToClassName($lang) {
    $langClassName = __NAMESPACE__ . "\\" . "Lang"
        . strtoupper(substr($lang, 0, 1))
        . strtolower(substr($lang, 1, strlen($lang)));
    return $langClassName;
  }

  public function getUserLink($user_id) {
    return "<a href=\"tg://user?id=$user_id\">$user_id</a>";
//    return "[$user_id](tg://user?id=$user_id)";
//    return "[$user_id](tg://user?id=$user_id)";
  }

  abstract function msg_welcome();

  abstract function msg_main_menu();

  abstract function kb_back();

  abstract function msg_select();

  abstract function msg_enter();

  abstract function langName();

  abstract function main_menu();

  abstract function aboutUs();

  public abstract function we_receive_input(string $fieldName);

  public abstract function now_enter(string $weRequireInput);

  public abstract function NoCommandFoundUseHelp($text);

  public abstract function querySearchForSongs();

  public abstract function select_language();

  public abstract function uploadSong();

  public abstract function pleaseSendMeSong();

  public abstract function ownerId();

  public abstract function song();

  public abstract function title();

  public abstract function emoji();

  public abstract function enterSongFirst();

  public abstract function newSongArrivedConfirmMessage($user_id);

  public abstract function newSongReceived();

  public abstract function your_song_confirmed_thank_you($title);

  public abstract function xItemsFound(int $sizeof);

  public abstract function ownerIdIs($owner_id);

  public abstract function confirmedBy($confirmed_by);

  public abstract function titleIs($title);

  public abstract function emojiIs($emoji);

  public function nextLine() {
    return PHP_EOL;
  }

  public abstract function xTimeUsed($use_count);

  public abstract function deActivate_now();

  public abstract function activate_now();

  public abstract function settings();

  public abstract function startPage();

  public abstract function botName();

  public abstract function help_description();

  public abstract function help_name();

  public abstract function help_usage();

  public abstract function cancel_Description();

  public abstract function cancel_Usage();

  public abstract function cancel_name();

  public abstract function whoami_description();

  public abstract function whoami_usage();

  public abstract function whoami_name();

  public abstract function whereami_description();

  public abstract function whereami_usage();

  public abstract function whereami_name();

  public abstract function start_name();

  public abstract function start_usage();

  public abstract function start_description();

  public abstract function most_used();

  public abstract function newest();

  public abstract function unconfirmed();

  public abstract function rejected();

  public abstract function redirectingTo(string $name);

  public abstract function popular();

  public abstract function songList();

  public abstract function sendMeText();

  public abstract function sendMeEmoji();

  public abstract function song_received_and_checked();

  public abstract function please_start_bot_for_more_result();

  public abstract function exactHelpType(string $string);

  public abstract  function textInputPage();

  public abstract function select_option_page() ;

  public abstract function edit_song();


}
