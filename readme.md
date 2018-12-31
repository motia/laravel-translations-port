# Laravel Translations Exporter
This package comes with a translation manager [laravel-translation-manager](https://github.com/vsch/laravel-translation-manager) and gives it the ability to import/export and a translation group to `json` or `yaml`.

## Setup
-  Install the package.
```
composer require motia/laravel-translation-exporter
```
- (optional if `autodiscovery` is on) Add the service provider `Motia\TransExport\TransExportProvider`
- Publish the config file using the command 
```
php artisan vendor:publish --provider="Motia\TransExport\TransExportProvider"
```

- Setup and configure [vsch/laravel-translation-manager](https://github.com/vsch/laravel-translation-manager)
- (optional) if you want to use `yaml` format run `composer install "symfony/yaml" "^4.0"`

## Usage
* Import translations from file to database
```
php artisan trans:import
```

* Export translations from database to file
```
php artisan trans:import
```

* Missing translations
This package comes with a controller to add missing translations and a helper function to add it to your routes.
 
```
// routes/api.php
<?php

use Motia\TransExport\Controller as TransExportController;

TransExportController::routes([
   'prefix' => 'trans-export',
   'middleware' => 'cors',
]);

```

```
// on your client app
axios.post('/missing', {
    key: 'namespaced.key',
    locale: 'en'
})
```
