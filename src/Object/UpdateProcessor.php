<?php
namespace VoiceTroll\Object;


class UpdateProcessor {
  private $update;
  private $commandClassPaths;

  function getCommandsClasspaths(): array {
    return $this->commandClassPaths;
  }

  function addCommandsClasspath($data) {
    if (is_string($data)) {
      $this->commandClassPaths[] = $data;
    }
  }
}