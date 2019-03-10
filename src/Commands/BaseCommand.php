<?php

namespace Motia\TranslationsPort\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

abstract class BaseCommand extends Command
{
    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getTranslationKey() {
        $group = $this->argument('group')
            ?: config('translations-port.groups')[0];

        if (!$group) {
            throw new \Exception('no translations groups configured');
        }
        return $group;
    }

    /**
     * @param $locale
     * @return string
     * @throws \Exception
     */
    protected function resolveLaravelFile($locale) {
        return resource_path('lang/'.$locale.'/'.$this->getTranslationKey().'.php');
    }

    protected function locales() {
        return Arr::wrap(config('translations-port.locales'));
    }

    protected function exportFormat()
    {
        return config('translations-port.export.format');
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

        $dir = config("translations-port.$type.dir");

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
