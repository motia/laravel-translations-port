<?php

namespace Motia\TransExport\Commands;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Yaml\Yaml;

class ImportFrontendTranslations extends BaseCommand {
    protected $signature = 'trans:fe2be';

    /**
     * @throws \Exception
     */
    public function handle() {
        foreach ($this->locales() as $locale) {
            $this->importLocal($locale);
        }

        Artisan::call('translations:import');
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
        if (strtolower($this->exportFormat()) === 'json') {
            return json_decode(file_get_contents($file), true);
        }
        return Yaml::parseFile($file);
    }
}
