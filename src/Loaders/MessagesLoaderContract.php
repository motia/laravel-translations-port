<?php

namespace Motia\TranslationsManager\Loaders;


interface MessagesLoaderContract
{
  /**
   * @param string $locale
   * @param string $group
   * @return array
   */
  public function messages($locale, $group);
}
