<?php

namespace Tests\Fields;

use Ignite\Translation\i18nGenerator;
use Tests\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class i18nGeneratorTest extends TestCase
{
    use TestHelpers;

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
