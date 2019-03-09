<?php

namespace Motia\TranslationsManager\Commands;

use Illuminate\Support\Facades\Artisan;
use Motia\TranslationsManager\Decoder;
use Symfony\Component\Yaml\Yaml;

class ImportFrontendTranslations extends BaseCommand {
    protected $signature = 'trans:fe2be';

    /**
     * @throws \Exception
     */
    public function handle() {
        $laravelFilesNotExisting = [];
        foreach ($this->locales() as $locale) {
            $this->importLocal($locale);
            $path = $this->resolveLaravelFile($locale);
            if (file_exists($path)) {
              $laravelFilesNotExisting[] = $path;
            }
        }

        Artisan::call('translations:import');

        if ($this->shouldUnlinkPhpFile()) {
          foreach ($laravelFilesNotExisting as $path) {
            unlink($path);
          }
        }
    }

    /**
     * @param $locale
     * @throws \Exception
     */
    private function importLocal($locale) {
        $laravelFile = $this->resolveLaravelFile($locale);
        $nuxtFile = $this->resolveImportPath($locale);
        if (!$nuxtFile || !file_exists($nuxtFile)){
            throw new \Exception('file to import does not exist');
        }

        $translations = file_exists($nuxtFile) ? $this->parseFile($nuxtFile) : [];
        $content = "<?php\n\nreturn ".var_export($translations, true).';';

        $this->info('Imported '. count($translations) . ' from '. $locale);

        file_put_contents(
            $laravelFile,
            $content
        );
    }

    private function parseFile($file) {
      $decoder = new Decoder();
      if (strtolower($this->exportFormat()) === 'json') {
        return $decoder->decodeFromJsonFile($file);
      }
      return $decoder->decodeFromYamlFile($file);
    }
}
