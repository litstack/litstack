<?php

namespace FjordTest\Translation;

use Fjord\Translation\Translator;
use FjordTest\BackendTestCase;

class TranslatorTest extends BackendTestCase
{
    /** @test */
    public function test_registered_translator_has_fjord_path()
    {
        $this->assertContains(
            fjord_path('resources/lang'),
            $this->app[Translator::class]->getPaths()
        );
    }
}
