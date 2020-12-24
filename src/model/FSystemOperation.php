<?php


namespace VoiceTroll\model;


use DirectoryIterator;
use framework\util\StringUtils;
use VoiceTroll\locale\Language;

class FSystemOperation {

  public static function haveThisLanguage(string $text,bool $isAlreadyCode=true) {
    $code=null;
    if (!$isAlreadyCode){
      $code = Language::getLanguageCode($text);
      if ($code===null){return false;}
    }
    $iterator = new DirectoryIterator(Language::getLocaleDir());
    foreach ($iterator as $item) {
      if (strlen($item)!==10)continue;
      if (!StringUtils::string_ends_with($item,".php"))continue;
      if (!StringUtils::string_starts_with($item,"Lang"))continue;
      if (strtolower(substr($item,4,2))===$code)return $code;
    }
    return false;
  }

  public static function getAllLanguageThatWeHave() {
    $iterator = new DirectoryIterator(Language::getLocaleDir());
    $codes=array();
    foreach ($iterator as $item) {
      if (strlen($item)!==10)continue;
      if (!StringUtils::string_ends_with($item,".php"))continue;
      if (!StringUtils::string_starts_with($item,"Lang"))continue;
      $codes[] = strtolower(substr($item, 4, 2));
    }
    $out=array();
    foreach ($codes as $code){
      $out[$code]=Language::getLanguageName($code);
    }
    return $out;
  }
}