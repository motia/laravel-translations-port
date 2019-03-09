<?php

namespace Motia\TranslationsPort\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Motia\TranslationsPort\Encoder;
use Motia\TranslationsPort\Loaders\MessagesLoaderContract;

class MessagesController extends Controller
{
    /**
     * @var MessagesLoaderContract
     */
    private $repository;

    public function __construct(MessagesLoaderContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $messages = $this->queryMessagesForRequest($request);

        return ['messages' => $messages];
    }

    /**
     * @param Request $request
     * @param Encoder $encoder
     * @return mixed
     * @throws \Exception
     */
    public function yaml(Request $request, Encoder $encoder)
    {
        $yaml = $encoder->encodeAsYaml(
            $this->queryMessagesForRequest($request)
        );

        return $yaml;
    }

    /**
     * @param Request $request
     * @param Encoder $encoder
     * @return false|string
     * @throws \Exception
     */
    public function json(Request $request, Encoder $encoder)
    {
        $json = $encoder->encodeAsJson(
            $this->queryMessagesForRequest($request)
        );

        return $json;
    }

    /**
     * @param array $config
     */
    public static function routes($config = [])
    {
        $config = array_merge(['prefix' => 'messages'], $config);
        Route::group($config, function () {
            Route::get('/{locale}.yaml', self::class . '@yaml');
            Route::get('/{locale}.json', self::class . '@json');
            Route::get('/{locale}', self::class);
        });
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    private function getLocale(Request $request)
    {
        $locales = config('translations-port.locales');
        $locale = $request->query('locale')
            ?: $request->route('locale');

        if (!in_array($locale, $locales)) {
            throw new \Exception("locale $locale not found");
        }

        return $locale;
    }


    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    private function getGroup(Request $request)
    {
        $groups = config('translations-port.groups');
        if (!count($groups)) {
            throw new \Exception('no groups..');
        }

        $group = $request->query('group')
            ?: $request->route('group')
            ?: config('translations-port.groups')[0];
        if ($group && !in_array($group, $groups)) {
            throw new \Exception("group $group not found");
        }

        return $group;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    protected function queryMessagesForRequest(Request $request): array
    {
        $locale = $this->getLocale($request);
        $group = $this->getGroup($request);

        $messages = $this->repository->messages($locale, $group);
        return $messages;
    }
}
