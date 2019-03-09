<?php

namespace Motia\TranslationsManager\Loaders;


class UrlLoader implements MessagesLoaderContract
{
  /**
   * @param string $locale
   * @param $group
   * @return array
   */
  public function messages($locale, $group)
  {
    $json = file_get_contents(config('trans-export.rest_url')."/$locale?group=$group");
    if (!$json) {
      throw new \RuntimeException('no response');
    }

    $data = json_decode($json, true);
    if (!$data) {
      throw new \RuntimeException('invalid response');
    }
    return $data;
  }
}
