<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace VoiceTroll\Commands;

use VoiceTroll\locale\Language;
use Longman\TelegramBot\Entities\PhotoSize;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Request;
use framework\util\MSG;

/**
 * User "/whoami" command
 *
 * Simple command that returns info about the current user.
 */
class WhoamiCMD extends UserCMD {
  public function execute() {
    $account = $this->getBundle()->account;
    $message = $this->getBundle()->update->getMessage();
    $from = $message->getFrom();
    $user_id = $from->getId();
    $chat_id = $message->getChat()->getId();
    $message_id = $message->getMessageId();

    $data = [
        'chat_id' => $chat_id,
        'reply_to_message_id' => $message_id,
    ];

    //Send chat action
    Request::sendChatAction([
        'chat_id' => $chat_id,
        'action' => 'typing',
    ]);

    $userLink =$this->getUserLanguage()->getUserLink($account->t_id);
    $caption = sprintf(
        'Your Id: %s' . PHP_EOL .
        'Name: %s %s' . PHP_EOL .
        'Username: %s',
        $userLink,
        $from->getFirstName(),
        $from->getLastName(),
        $from->getUsername()
    );

    //Fetch user profile photo
    $limit = 10;
    $offset = null;
    $response = Request::getUserProfilePhotos(
        [
            'user_id' => $user_id,
            'limit' => $limit,
            'offset' => $offset,
        ]
    );

    if (!$response->isOk()) {
      return false;
    }
    /** @var UserProfilePhotos $user_profile_photos */
    $user_profile_photos = $response->getResult();
    if ($user_profile_photos->getTotalCount() > 0) {
      $photos = $user_profile_photos->getPhotos();

      /** @var PhotoSize $photo */
      $photo = $photos[0][2];
      $file_id = $photo->getFileId();

      $data['photo'] = $file_id;
      $data['caption'] = $caption;
      $data['parse_mode'] = "HTML";
      MSG::messageForDebug($caption);
      $result = Request::sendPhoto($data);

//                //Download the photo after send message response to speedup response
//                $response2 = Request::getFile(['file_id' => $file_id]);
//                if ($response2->isOk()) {
//                    /** @var File $photo_file */
//                    $photo_file = $response2->getResult();
//                    Request::downloadFile($photo_file);
//                }
      if (!$result->isOk()) {
        MSG::messageForDebug($result->getDescription());
      }
      return $result->isOk();
    }

    //No Photo just send text
    $data['text'] = $caption;

    return Request::sendMessage($data);
  }

  public static function getName(Language$lang): string {
    return $lang->whoami_name();
  }

  public static function getUsage(Language$lang): string {
    return $lang->whoami_usage();
  }

  public static function shouldShow(): bool {
    return true;
  }

  public function shouldWorkForThisUser(): bool {
    return true;
  }

  public static function getCommandName(): string {
    return "whoami";
  }

  public static function getDescription(Language $lang): string {
    return $lang->whoami_description();
  }
}
