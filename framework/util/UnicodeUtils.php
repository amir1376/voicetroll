<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/19/2019
 * Time: 10:39 AM
 */

namespace framework\util;


class UnicodeUtils {
  function _uniord($c) {
    if (ord($c{0}) >=0 && ord($c{0}) <= 127)
      return ord($c{0});
    if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
      return (ord($c{0})-192)*64 + (ord($c{1})-128);
    if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
      return (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
    if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
      return (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
    if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
      return (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
    if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
      return (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
    if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
      return FALSE;
    return 0;
  }   //  function _uniord()
  function _unichr($o) {
    if (function_exists('mb_convert_encoding')) {
      return mb_convert_encoding('&#'.intval($o).';', 'UTF-8', 'HTML-ENTITIES');
    } else {
      return chr(intval($o));
    }
  }   // function _unichr()
}