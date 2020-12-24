<?php

namespace VoiceTroll\locale;
require_once("Language.php");

class LangEn extends Language {
  function msg_welcome() {
    return "welcome to Audimoji \n I hope  you enjoy";
  }
  function msg_main_menu() {
    return "main menu";
  }
  function kb_back() {
    return "back";
  }
  function msg_select() {
    return "select once";
  }
  function langName() {
    return "English";
  }
  function msg_enter() {
    return "Enter value";
  }
  function main_menu() {
    return "Main menu";
  }
  function aboutUs() {
    return "Amir AB";
  }
  public function we_receive_input(string $fieldName) {
    return "We receive input";
  }
  public function now_enter(string $weRequireInput) {
    return "please enter the $weRequireInput;";
  }
  public function NoCommandFoundUseHelp($text) {
    return "command $text not found;";

  }
  public function querySearchForSongs() {
    return "Search for songs;";
  }
  public function select_language() {
    return "ُLanguage;";
  }
  public function uploadSong() {
    return "Upload a song!";
  }
  public function pleaseSendMeSong() {
    return "Send me the sound that you want!";

  }
  public function ownerId() {
    return "Owner";
  }
  public function song() {
    return "song";
  }
  public function title() {
    return "title";
  }
  public function emoji() {
    return "emoji";
  }
  public function enterSongFirst() {
    return "Please send me voice first!";
  }
  public function newSongArrivedConfirmMessage($user_id) {
    return "we received new song from ".$this->getUserLink($user_id)."is this ok?";
  }
  public function your_song_confirmed_thank_you($title) {
    return "your song with title $title confirmed and appeared in results thank you";
  }
  public function xItemsFound(int $sizeof) {
    return "$sizeof item/s found";
  }
  public function ownerIdIs($owner_id) {
    return "owner is :".$this->getUserLink($owner_id);
  }
  public function confirmedBy($confirmed_by) {
    return "confirmed by :".$this->getUserLink($confirmed_by);
  }
  public function titleIs($title) {
    return "title : $title";
  }
  public function emojiIs($emoji) {
    return "emoji : $emoji";
  }
  public function xTimeUsed($use_count) {
    return "$use_count times used";
  }

  public function newSongReceived() {
    return "new song received";
  }

  public function deActivate_now() {
    return "deactivate";
  }

  public function activate_now() {
    return "activate";
  }

  public function settings() {
    return "Settings";
  }

  public function startPage() {
    return "Start page";
  }
  public function botName() {
    return "Voice Troll";
  }

  public function help_description() {
    return "help about commands";
  }

  public function help_name() {
    return "help";
    
  }

  public function help_usage() {
    return "when you want to know about commands";
  }

  public function cancel_Description() {
    return "cancel current operation";
  }

  public function cancel_Usage() {
    return "when you want cancel current operation come to me";
  }

  public function cancel_name() {
    return "cancel";
  }

  public function whoami_description() {
    return "send your account information";
  }

  public function whoami_usage() {
    return "tell you, who are you and account info";
  }

  public function whoami_name() {
    return "Who am I";
  }

  public function whereami_description() {
    return "your current direction in bot";
  }

  public function whereami_usage() {
    return "When you want to know where you are in bot";
  }

  public function whereami_name() {
    return "Where am I";
  }

  public function start_name() {
    return "start";
  }

  public function start_usage() {
    return "Start bot";
  }

  public function start_description() {
    return "When you want start the bot click on me";
  }

  public function most_used() {
    return "most used";
  }

  public function newest() {
    return "newest";
  }

  public function unconfirmed() {
    return "unconfirmed";
  }

  public function rejected() {
    return "rejected";
  }

  public function redirectingTo(string $name) {
    return "redirecting to $name";
  }

  public function popular() {
    return "popular";
  }

  public function songList() {
    return "Song list";
  }

  public function sendMeText() {
    return "Please send me text";
  }

  public function sendMeEmoji() {
    return "Please send me emoji";
  }

  public function song_received_and_checked() {
    return "Voice has been saved and becomes to search result as soon as possible";
  }

  public function please_start_bot_for_more_result() {
    return "for more results please click here and click start";
  }

  public function exactHelpType(string $string) {
    return "For exact command help type: $string {command}";
  }

  public function textInputPage() {
    return "text input page";
  }

  public function select_option_page() {
    return "Select option page";
  }

  public function edit_song() {
    return "Edit song";
  }
}