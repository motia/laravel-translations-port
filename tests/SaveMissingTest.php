<?php

use Illuminate\Support\Str;
use Tests\TestCase;
use Vsch\TranslationManager\Models\Translation;

class SaveMissingTest extends TestCase
{
    private $savedTranslation = null;


    public function testSaveMissingSuccess()
    {
        $router = $this->app->get('router');
        $router->group([
            'prefix' => 'test-export-test'
        ], function () use ($router) {
            $router->post('missing', \Motia\TranslationsManager\Controllers\MissingMessagesController::class.'@missing');
        });

        $key = Str::random(10);

        $testData = [
            'locale' => 'en',
            'key' => $key
        ];

        $this->postJson('test-export-test/missing', $testData)
            ->assertJson($testData);

        $this->assertDatabaseHas((new Translation)->getTable(), $testData);
        $this->savedTranslation = $key;
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void {
        $item = Translation::query()->where([
            'key' => $this->savedTranslation
        ])->first();

        $item->delete();

        parent::tearDown();
    }
}
