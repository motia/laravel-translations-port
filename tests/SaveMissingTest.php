<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Vsch\TranslationManager\Models\Translation;

class SaveMissingTest extends TestCase
{
    private $savedTransaltion = null;


    public function testSaveMissingSuccess()
    {
        $router = $this->app->get('router');
        $router->group([
            'prefix' => 'test-export-test'
        ], function () use ($router) {
            $router->post('missing', '\Motia\TransExport\Controller@missing');
        });

        $key = str_random(10);

        $testData = [
            'locale' => 'en',
            'key' => $key
        ];

        $this->postJson('test-export-test/missing', $testData)
            ->assertJson($testData);

        $this->assertDatabaseHas((new Translation)->getTable(), $testData);
        $this->savedTransaltion = $key;
    }

    protected function tearDown() {
        $item = Translation::query()->where([
            'key' => $this->savedTransaltion
        ])->first();

        $item->delete();

        parent::tearDown();        
    }
}
