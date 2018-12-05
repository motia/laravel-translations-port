<?php

namespace Motia\TransExport\Commands;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Yaml\Yaml;

class ExportFrontendTranslations extends BaseCommand {
    protected $signature = 'trans:be2fe';

    /**
     * @throws \Exception
     */
    public function handle() {
        Artisan::call('translations:export', ['group' => $this->getTranslationKey()]);

        foreach ($this->locales() as $locale) {
            $this->exportLocale($locale);
        }
    }

    /**
     * @param $locale
     * @throws \Exception
     */
    private function exportLocale($locale) {
        $laravelFile = $this->resolveLaravelFile($locale);

        $dump = file_exists($laravelFile) ? include($laravelFile) : [];
        $nuxtFile = $this->resolveExportPath($locale);

        $yml = $this->encodeData($dump);

        $this->info('Exported '. count($dump) . ' from '. $locale);

        file_put_contents(
            $nuxtFile,
            $yml
        );
    }

    private function encodeData($data)
    {
        if (strtolower($this->exportFormat()) === 'json') {
            return json_encode($data);
        }
        return Yaml::dump($data, 10, 2);
    }
}
