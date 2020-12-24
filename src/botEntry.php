<?php
$messageTypes=array(
    "query",
    "pv",
    "group",
);
function exists($arr,$value){
  foreach ($arr as $x){
    if ($x===$value)return true;
  }
  return false;
}
$messageType="";
if (!exists($messageTypes,$messageType)){
//  sendMessageToDebugger("");
  exit;
}

