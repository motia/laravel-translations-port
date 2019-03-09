<?php

namespace Motia\TranslationsPort;


use Symfony\Component\Yaml\Yaml;

class Encoder
{
  public function encodeAsYaml($data) {
    return Yaml::dump($data, 10, 2);
  }

  public function encodeAsJson($data){
    return json_encode($data);
  }
}
