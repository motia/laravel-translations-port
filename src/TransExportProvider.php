<?php

namespace Motia\TransExport;

use Illuminate\Support\ServiceProvider;

class TransExportProvider extends ServiceProvider
{
    private  function getConfigFilePath() {
        return __DIR__.'/config.php';
    }

    public function register () {
        $this->mergeConfigFrom(
            $this->getConfigFilePath(), 'trans-export'
        );
    }

    public function boot()
    {
        $this->publishes([
           $this->getConfigFilePath()  => config_path('trans-export.php'),
        ]);

        $this->commands([
            Commands\ExportFrontendTranslations::class,
            Commands\ImportFrontendTranslations::class,
        ]);
    }
}
