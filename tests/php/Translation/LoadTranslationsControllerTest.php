<?php

namespace FjordTest\Translation;

use FjordTest\BackendTestCase;
use FjordTest\Traits\CreateFjordUsers;

class LoadTranslationsControllerTest extends BackendTestCase
{
    use CreateFjordUsers;

    /** @test */
    public function test_lang_js_route()
    {
        app('config')->set('translation.locales', ['en']);

        $this->actingAs($this->admin, 'fjord');
        $response = $this->get(fjord()->url('lang.js'));
        $response->assertStatus(200);
        $this->assertStringStartsWith('window.i18n_m = {', $response->getContent());
    }
}
