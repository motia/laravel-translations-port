<?php

namespace Motia\TranslationsPort;


use Symfony\Component\Yaml\Yaml;

class Decoder
{
  public function decodeFromYamlFile($file) {
    return Yaml::parseFile($file);
  }

  public function decodeFromJsonFile($file)
  {
    return json_decode(file_get_contents($file), true);
  }
}
