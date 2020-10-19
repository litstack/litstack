<?php

namespace Tests\Translation;

use Ignite\Translation\Translator;
use Tests\BackendTestCase;

class TranslatorTest extends BackendTestCase
{
    /** @test */
    public function test_registered_translator_has_path_to_package_translations()
    {
        $this->assertContains(
            litstack_lang_path(),
            $this->app[Translator::class]->getPaths()
        );
    }
}
