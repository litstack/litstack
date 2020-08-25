<?php

namespace Tests\Translation;

use Ignite\Translation\Translator;
use Tests\BackendTestCase;

class TranslatorTest extends BackendTestCase
{
    /** @test */
    public function test_registered_translator_has_lit_path()
    {
        $this->assertContains(
            lit_path('resources/lang'),
            $this->app[Translator::class]->getPaths()
        );
    }
}
