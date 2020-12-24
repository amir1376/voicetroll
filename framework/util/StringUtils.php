<?php

namespace framework\util;
class StringUtils {
  public static function string_contains($text, $search) {
    if (preg_match("/{$search}/i", $text)) {
      return true;
    } else return false;
  }

//echo (int)string_starts_with("salam","sal");
//echo (int)string_ends_with("salam","lam");
  public static function string_starts_with($string, $prefix) {
    return substr($string, 0, strlen($prefix)) === $prefix;
  }

  public static function string_ends_with($string, $postfix) {
    return substr($string, strlen($string) - strlen($postfix), strlen($string)) === $postfix;
  }

  public static function convertToClassName($str): string {
    $array = array();
    $mustBeUpper = true;
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
      if ($str[$i] === "_") {
        $mustBeUpper = true;
        continue;
      } elseif ($mustBeUpper) {
        $array[] = strtoupper($str[$i]);
        $mustBeUpper = false;
        continue;
      }
      $array[] = $str[$i];
    }
    return implode("", $array);
  }

  public static function arrayToString(array $arr) {
    $out = "";
    foreach ($arr as $peace) {
      is_string($peace) && $out .= $peace;
    }
    return $out;
  }

  /**
   * @param $emoji
   * @return false|string
   */
  public static function getFirstEmoji($emoji){
    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $matched = preg_match($regex_emoticons, $emoji,$result);
    return $matched?$result[0]:$matched;
  }
}
