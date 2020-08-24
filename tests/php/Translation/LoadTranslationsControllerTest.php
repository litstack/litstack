<?php

namespace Tests\Translation;

use Tests\BackendTestCase;
use Tests\Traits\CreateLitUsers;

class LoadTranslationsControllerTest extends BackendTestCase
{
    use CreateLitUsers;

    /** @test */
    public function test_lang_js_route()
    {
        app('config')->set('translation.locales', ['en']);

        $this->actingAs($this->admin, 'lit');
        $response = $this->get(lit()->url('lang.js'));
        $response->assertStatus(200);
        $this->assertStringStartsWith('window.i18n_m = {', $response->getContent());
    }
}
