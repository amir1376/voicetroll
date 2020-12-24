<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/26/2019
 * Time: 8:13 PM
 */

namespace VoiceTroll\view;


use Longman\TelegramBot\Request;

class MessageSender {
  function sendMessage(
      $chat_id,
      $from_chat_id,
      $disable_notification,
      $message_id
  ) {
    return Request::sendMessage([
        "chat_id" => $chat_id,
        "from_chat_id" => $from_chat_id,
        "disable_notification" => $disable_notification,
        "message_id" => $message_id
    ]);
  }

  function sendPhoto(
      $chat_id,
      $photo,
      $caption,
      $parse_mode,
      $disable_notification,
      $reply_to_message_id,
      $reply_markup
  ) {
    return Request::sendPhoto([
        "chat_id" => $chat_id,
        "photo" => $photo,
        "caption" => $caption,
        "parse_mode" => $parse_mode,
        "disable_notification" => $disable_notification,
        "reply_to_message_id" => $reply_to_message_id,
        "reply_markup" => $reply_markup,
    ]);
  }

  function sendVoice(
      $chat_id,
      $voice,
      $caption = null,
      $parse_mode = null,
      $duration = null,
      $disable_notification = null,
      $reply_to_message_id = null,
      $reply_markup = null
  ) {
    return Request::sendVoice([
        "chat_id" => $chat_id,
        "voice" => $voice,
        "caption" => $caption,
        "parse_mode" => $parse_mode,
        "duration" => $duration,
        "disable_notification" => $disable_notification,
        "reply_to_message_id" => $reply_to_message_id,
        "reply_markup" => $reply_markup,
    ]);
  }


  function sendAudio(
      $chat_id,
      $audio,
      $caption = null,
      $parse_mode = null,
      $duration = null,
      $performer = null,
      $title = null,
      $thumb = null,
      $disable_notification = null,
      $reply_to_message_id = null,
      $reply_markup = null
  ) {
    return Request::sendAudio([
        "chat_id" => $chat_id,
        "audio" => $audio,
        "caption" => $caption,
        "parse_mode" => $parse_mode,
        "duration" => $duration,
        "performer" => $performer,
        "title" => $title,
        "thumb" => $thumb,
        "disable_notification" => $disable_notification,
        "reply_to_message_id" => $reply_to_message_id,
        "reply_markup" => $reply_markup,
    ]);
  }

  function VoiceMessageBuilder() {
    return new VoiceMessageBuilder();
  }

  function TextMessageBuilder() {
    return new TextMessageBuilder();
  }

  function AudioMessageBuilder() {
    return new AudioMessageBuilder();
  }

  function PhotoMessageBuilder() {
    return new PhotoMessageBuilder();
  }
}

abstract class Builder {
  function getData(){
    return get_object_vars($this);
  }
}

class VoiceMessageBuilder extends Builder {

}

class TextMessageBuilder extends Builder {

}

class AudioMessageBuilder extends Builder {

}

class PhotoMessageBuilder extends Builder {

}
