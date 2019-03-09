<?php


/**
 * Possible values for repository are :
 *  - \Motia\TranslationsPort\Repositories\FileRepository::class
 *  - \Motia\TranslationsPort\Repositories\VschTranslationsLoader::class
 *  - \Motia\TranslationsPort\Repositories\RestRepository::class
 */
return [
    'loader' => \Motia\TranslationsPort\Loaders\VschTranslationsLoader::class,
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
