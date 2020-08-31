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
            lit_vendor_path('resources/lang'),
            $this->app[Translator::class]->getPaths()
        );
    }
}
