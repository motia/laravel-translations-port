<?php

namespace Motia\TransExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vsch\TranslationManager\Models\Translation;

class Controller {
    public function missing (Request $request){
        $request->validate([
            'key' => 'required',
            'locale' => 'required|in:'.implode(',', config('trans-export.locales')),
        ]);
        $key = $request->input('key');
        $locale = $request->input('locale');

        $group = config('trans-export.group');

        return Translation::firstOrCreate(array(
            'key' => $key,
            'group' => $group,
            'locale' => $locale,
        ));
    }
    
    public static function routes($config = []) {
        Route::group($config, function () {
            Route::post('missing', '\Motia\TransExport\Controller@missing');
        });
    }
}
