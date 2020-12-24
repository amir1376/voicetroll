<?php
namespace framework\util;
class JSONWrapper{
  public function __construct(array $data ) {
    $this->wrap($data);
  }

  private function wrap(array $data) {
    foreach (get_object_vars($this) as $key=>$value){
      if (key_exists($key,$data)){
        $this->$key=$data[$key];
      }
    }
  }
}