<?php


/**
 * Possible values for repository are :
 *  - \Motia\TranslationsManager\Repositories\FileRepository::class
 *  - \Motia\TranslationsManager\Repositories\VschTranslationsLoader::class
 *  - \Motia\TranslationsManager\Repositories\RestRepository::class
 */
return [
    'loader' => \Motia\TranslationsManager\Loaders\VschTranslationsLoader::class,
    'rest_url' => null,
    'locales' => ['en'],
    'import' => [
        'dir' => null,
    ],
    'export' => [
        'dir' => null,
        'format' => 'json'
    ],
];
