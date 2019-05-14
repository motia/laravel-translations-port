<?php

namespace Motia\TranslationsPort\Loaders;


class UrlLoader implements MessagesLoaderContract
{
  /**
   * @param string $locale
   * @param $group
   * @return array
   */
  public function messages($locale, $group)
  {
    $baseUrl = config('translations-port.rest_url');
    if (!$baseUrl) {
      throw new \LogicException('translations-port.rest_url not configured for UrlLoader');
    }

    $json = file_get_contents($baseUrl."/$locale?group=$group");
    if (!$json) {
      throw new \RuntimeException('no response');
    }

    $data = json_decode($json, true);

    if (!$data) {
      throw new \RuntimeException('invalid response');
    }
    return $data['messages'] ?? [];
  }
}
