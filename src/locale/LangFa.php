<?php

namespace VoiceTroll\locale;
require_once("Language.php");

class LangFa extends Language {

  function msg_welcome() {
    return "به ".$this->botName()." خوش اومدی\nامیدوارم لذت ببری";
  }

  function msg_main_menu() {
    return "منوی اصلی";
  }

  function kb_back() {
    return "بازگشت";
  }

  function msg_select() {
    return "یک گزینه رو انتخاب کن";
  }

  function langName() {
    return "Persian";
  }

  function msg_enter() {
    return "مقداری را وارد کنید";
  }

  function main_menu() {
    return "منوی اصلی";
  }

  function aboutUs() {
    return "درباره ما";
  }

  public function we_receive_input(string $fieldName) {
    return "ما یک ورودی جدید دریافت کردیم";
  }

  public function now_enter(string $weRequireInput) {
    return "حالا $weRequireInput رو بفرست!";
  }

  public function NoCommandFoundUseHelp($text) {
    return "منظورتو نگرفتم!";

  }

  public function querySearchForSongs() {
    return "میتونی دنبال وویس ها بگردی";
  }

  public function select_language() {
    return "انتخاب زبان";
  }

  public function uploadSong() {
    return "آپلود صداهای باحال";
  }

  public function pleaseSendMeSong() {
    return "وویسی که میخوای رو بفرست";

  }

  public function ownerId() {
    return "ارسال کننده";
  }

  public function song() {
    return "وویس";
  }

  public function title() {
    return "عنوانش";
  }

  public function emoji() {
    return "شکلک";
  }

  public function enterSongFirst() {
    return "قبل از هر کاری وویس رو بده بیاد";
  }

  public function newSongArrivedConfirmMessage($user_id) {
    return "یک وویس از  ".$this->getUserLink($user_id).")قبولش کنیم؟";
  }

  public function your_song_confirmed_thank_you($title) {
    return "وویست با این عنوان $title تایید شد و تو نتیجه ها نشون داده میشه دمت گرم!";
  }

  public function xItemsFound(int $sizeof) {
    return "$sizeof مورد پیدا شد";
  }

  public function newSongReceived() {
    return "وویس جدید اومده...";
  }

  public function ownerIdIs($owner_id) {
    return "فرستنده :"." ".$this->getUserLink($owner_id);
  }

  public function confirmedBy($confirmed_by) {
    return "تایید کننده :"." ".$this->getUserLink($confirmed_by);
  }

  public function titleIs($title) {
    return "عنوان:".($title);
  }

  public function emojiIs($emoji) {
    return "توضیحات:".($emoji);
  }

  public function xTimeUsed($use_count) {
    return$use_count. "بار استفاده شده";
  }

  public function deActivate_now() {
    return "غیرفعالش کن";
  }

  public function activate_now() {
    return "فعالش کن";
  }

  public function settings() {
    return "تنظیمات";
  }
  public function startPage() {
    return "صفحه شروع";
  }
  public function botName() {
    return "وویس ترول";
  }

  public function help_description() {
    return "اگر کاربرد دستوری را نمیدونی از من کمک بگیر";
  }

  public function help_name() {
    return "راهنما";
  }

  public function help_usage() {
    return "مشاهده لیست دستورات";
  }

  public function cancel_Description() {
    return "لغو کردن عملیات حال حاظر";
  }

  public function cancel_Usage() {
    return "پشیمون شدن";
  }

  public function cancel_name() {
    return "انصراف";

  }

  public function whoami_description() {
    return "دیدن اطلاعات شخصی";

  }

  public function whoami_usage() {
    return "وثتی میخوای اطلاعاتتو ببینی بیا سراغ من";

  }

  public function whoami_name() {
    return "من کی ام؟";

  }

  public function whereami_description() {
    return "مسیر حال حاظر";

  }

  public function whereami_usage() {
    return "وثتی میخوای بدونی کدوم قسمت از رباتی بیا سراغ من";

  }

  public function whereami_name() {
    return "من کجام؟";

  }

  public function start_name() {
    return "شروع";

  }

  public function start_usage() {
    return "شروع کار با ربات";

  }

  public function start_description() {
    return "وقتی تازه میخوای از ربات استفاده کنی باید بیای سراغ من";

  }

  public function most_used() {
    return "بیشترین استفاده";
  }

  public function newest() {
    return "جدیدترین ها";
  }

  public function unconfirmed() {
    return "تایید نشده ها";
  }

  public function rejected() {
    return "رد شده ها";
  }

  public function redirectingTo(string $name) {
    return "رفتن به"." ".$name;
  }

  public function popular() {
    return "پر طرفدار";
  }

  public function songList() {
    return "لیست آهنگها";
  }

  public function sendMeText() {
    return "فقط متن بفرست";
  }

  public function sendMeEmoji() {
    return "شکلک بفرست";
  }

  public function song_received_and_checked() {
    return "ازین که این وویس فرستادی ممنونم به زودی تو لیست جست و جو به نمایش در میاد";
  }

  public function please_start_bot_for_more_result() {
    return "لطفا برای مشاهده نتایج بیشتر کلیک کرده و ربات را استارت کنید";
  }

  public function exactHelpType(string $string) {
    return "برای توضیحات کامل دستور "."$string"."و سپس "." { تام دستور } " ."را تایپ کنید";
  }

  public function textInputPage() {
    return "صفحه دریافت ورودی";
  }

  public function select_option_page() {
    return "صفحه انتخاب گزینه";
  }

  public function edit_song() {
    return "ویرایش اطلاعات صدا";
  }
}