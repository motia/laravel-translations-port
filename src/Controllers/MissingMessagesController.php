<?php

namespace Motia\TranslationsPort\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vsch\TranslationManager\Models\Translation;

class MissingMessagesController
{
    use ValidatesRequests;

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bulkMissing(Request $request)
    {
        $allowedGroups = config('translations-port.groups');
        if (!count($allowedGroups)) {
            throw new \Exception('no groups are allowed');
        }
        $inputs = $this->validate($request, [
            'items' => 'min:1|max:20',
            'group' => 'in|' . implode($allowedGroups),
            'items.*.key' => 'required',
            'items.*.locale' => 'required|in:' . implode(',', config('translations-port.locales')),
        ]);

        $group = $inputs['group'] ?? $allowedGroups[0];

        $created = [];
        foreach ($inputs['items'] ?? [] as $input) {
            $created[] = Translation::query()->firstOrCreate(array_merge($input, ['group' => $group]));
        }
        return $created;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Validation\ValidationException
     */
    public function missing(Request $request)
    {
        $inputs = $this->validate($request, [
            'key' => 'required',
            'locale' => 'required|in:' . implode(',', config('translations-port.locales')),
        ]);

        $group = config('translations-port.group');

        return Translation::query()->firstOrCreate(array_merge($inputs, ['group' => $group]));
    }

    /**
     * @param array $config
     */
    public static function routes($config = [])
    {
        $config = array_merge(['prefix' => 'missing-messages'], $config);

        Route::group($config, function () {
            Route::post('bulk', self::class . '@bulkMissing');
            Route::post('missing', self::class . '@missing');
        });
    }
}
