<?php
namespace framework\common;
interface Connection{
  public function connect();
  public function disconnect();
  public function isConnected();
}