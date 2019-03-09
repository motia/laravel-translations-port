<?php

namespace Motia\TranslationsManager\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    protected function getTranslationKey() {
        return config('trans-export.group');
    }

    protected function resolveLaravelFile($locale) {
        return resource_path('lang/'.$locale.'/'.$this->getTranslationKey().'.php');
    }

    protected function locales() {
        return array_wrap(config('trans-export.locales'));
    }

    protected function exportFormat()
    {
        return config('trans-export.export.format');
    }


    /**
     * @param $locale
     * @param $type
     * @return bool|string
     * @throws \Exception|\InvalidArgumentException
     */
    private function resolvePath($locale, $type) {
        if ($type !== 'import' && $type !== 'export') {
            throw new \InvalidArgumentException('invalid type '.$type);
        }

        $dir = config("trans-export.$type.dir");

        if (!$dir) {
            throw new \Exception("$type directory not configured");
        }
        $path = $dir.'/'.$locale.'.'.$this->exportFormat();
        $path = file_exists($path) ? realpath($path) : $path;

        if (!$path) {
            throw new \Exception('import path is invalid');
        }

        return $path;
    }


    /**
     * @param $locale
     * @return bool|string
     * @throws \Exception
     */
    protected function resolveImportPath($locale) {
        return $this->resolvePath($locale, 'import');
    }

    /**
     * @param $locale
     * @return string
     * @throws \Exception
     */
    protected function resolveExportPath($locale) {
        return $this->resolvePath($locale, 'export');
    }

    protected function shouldUnlinkPhpFile() {
        return false;
    }
}
