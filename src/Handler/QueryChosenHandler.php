<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 9/23/2019
 * Time: 4:41 AM
 */

namespace VoiceTroll\Handler;


use VoiceTroll\model\DBOperation;
use VoiceTroll\Object\Bundle;

class QueryChosenHandler extends Handler {

  /**
   * @param Bundle $bundle
   * @return bool
   */
  public function handle($bundle) {
    $update = $bundle->update;
    $result = $update->getChosenInlineResult();
    DBOperation::updateAccountUseCountAndSongUseCount(
        $result->getResultId(), $result->getFrom()->getId()
    );
    return true;
  }
}