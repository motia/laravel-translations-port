<?php

namespace Motia\TranslationsPort\Loaders;


class LangFileLoader implements MessagesLoaderContract
{
  /**
   * @param string $locale
   * @param string $group
   * @return array
   * @throws \Exception
   */
  public function messages($locale, $group)
  {
    $filePath = resource_path("lang/$locale/$group.php");
    if (!file_exists($filePath)) {
      throw new \InvalidArgumentException("Locale file $filePath for locale=$locale not found");
    }
    return require($filePath);
  }
}
