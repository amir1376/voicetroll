<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 6:54 AM
 */

namespace VoiceTroll\Object;


interface InputReceiver {
  public function onInputReceived($key,$value);
}