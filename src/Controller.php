<?php

namespace Motia\TransExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vsch\TranslationManager\Models\Translation;

class Controller {
    public function bulkMissing (Request $request){
      $inputs = $request->validate([
        'items' => 'min:1|max:20',
        'items.*.key' => 'required',
        'items.*.locale' => 'required|in:'.implode(',', config('trans-export.locales')),
      ]);

      $group = config('trans-export.group');

      foreach ($inputs['items'] ?? [] as $input) {
        $created = Translation::firstOrCreate(array_merge($input, ['group' => $group]));
      }
      return $created;
    }

    public function missing (Request $request){
        $inputs = $request->validate([
            'key' => 'required',
            'locale' => 'required|in:'.implode(',', config('trans-export.locales')),
        ]);

        $group = config('trans-export.group');

        return Translation::firstOrCreate(array_merge($inputs, ['group' => $group]));
    }
    
    public static function routes($config = []) {
        Route::group($config, function () {
            Route::post('bulk-missing', '\Motia\TransExport\Controller@bulkMissing');
            Route::post('missing', '\Motia\TransExport\Controller@missing');
        });
    }
}
