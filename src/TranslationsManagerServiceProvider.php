<?php

namespace Motia\TranslationsManager;


use Illuminate\Support\ServiceProvider;
use Motia\TranslationsManager\Loaders\LangFileLoader;
use Motia\TranslationsManager\Loaders\MessagesLoaderContract;

class TranslationsLoaderServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(
      $this->getConfigFilePath(), 'trans-export'
    );

    $this->app->singleton(
      MessagesLoaderContract::class,
      config('trans-export.loader', LangFileLoader::class)
    );

  }

  public function boot()
  {
    $this->commands([
        Commands\ImportFrontendTranslations::class,
    ]);
    $this->publishes([
        $this->getConfigFilePath() => config_path('trans-export.php'),
    ]);
  }

  private function getConfigFilePath()
  {
    return __DIR__ . '/config.php';
  }
}
