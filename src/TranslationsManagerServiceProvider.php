<?php

namespace Motia\TranslationsPort;


use Illuminate\Support\ServiceProvider;
use Motia\TranslationsPort\Loaders\LangFileLoader;
use Motia\TranslationsPort\Loaders\MessagesLoaderContract;

class TranslationsLoaderServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(
      $this->getConfigFilePath(), 'translations-port'
    );

    $this->app->singleton(
      MessagesLoaderContract::class,
      config('translations-port.loader', LangFileLoader::class)
    );

  }

  public function boot()
  {
    $this->commands([
        Commands\ImportFrontendTranslations::class,
    ]);
    $this->publishes([
        $this->getConfigFilePath() => config_path('translations-port.php'),
    ]);
  }

  private function getConfigFilePath()
  {
    return __DIR__ . '/config.php';
  }
}
