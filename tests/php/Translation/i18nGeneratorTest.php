<?php

namespace FjordTest\Fields;

use Fjord\Translation\i18nGenerator;
use FjordTest\BackendTestCase;

class i18nGeneratorTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->generator = new i18nGenerator([]);
    }

    /** @test */
    public function test_convertString_method()
    {
        $result = $this->callUnaccessibleMethod(
            $this->generator,
            'convertString',
            ['dummy :link text']
        );
        $this->assertEquals('dummy {link} text', $result);
    }
}
