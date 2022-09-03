<?php

declare(strict_types=1);

namespace BlogPortal\Api;

class Configuration {
  private static $data = [];

  public static function preload() {
    self::$data = \yaml_parse_file(getcwd().'/api/config.yml');
  }

  public function get(string $fullPath) {
    $paths = explode('.', $fullPath);

    $data = self::$data;
    foreach($paths as $path) {
      $data = $data[$path] ?? null;
      if($data === null)
        break;
    }

    return $data;
  }
}
