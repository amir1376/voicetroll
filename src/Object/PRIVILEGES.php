<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/19/2019
 * Time: 1:08 PM
 */

namespace VoiceTroll\Object;


class PRIVILEGES {
  const ACCEPT_SONG = 1;
  const REJECT_SONG = 2;
  const EDIT_SONG_ATTRS = 4;
  const EDIT_WHOLE_SONG = 7;
  const CAN_SEARCH = 32;
  const BROADCAST = 64;
  const ADD_ADMIN = 128;
}