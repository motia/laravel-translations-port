<?php

namespace Motia\TranslationsManager\Loaders;

use Illuminate\Support\Arr;
use Vsch\TranslationManager\Models\Translation;
use Vsch\TranslationManager\Repositories\Interfaces\ITranslatorRepository;

class VschTranslationsLoader implements MessagesLoaderContract
{
  /**
   * @var ITranslatorRepository
   */
  private $translatorRepository;

  public function __construct(ITranslatorRepository $translatorRepository)
  {
    $this->translatorRepository = $translatorRepository;
  }

  /**
   * @param string $locale
   * @param string $group
   * @return array
   * @throws \Exception
   */
  public function messages($locale, $group)
  {
    $rawTranslations = (new Translation())->query()
      ->where('group', $group)
      ->where('locale', $locale)
      ->whereNotNull('value');

    $rawTranslations = $rawTranslations
      ->orderby('key')
      ->get();

    return $this->makeTree($rawTranslations)[$locale][$group] ?? [];
  }

  protected function makeTree($translations)
  {
    $array = array();
    foreach ($translations as $translation) {
      Arr::set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
    }
    return $array;
  }
}
