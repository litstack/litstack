<?php

namespace Tests\Application;

use Fjord\Support\Facades\FjordApp;
use Fjord\Translation\Translator;
use FjordTest\BackendTestCase;
use Mockery as m;

class ApplicationTests extends BackendTestCase
{
    /** @test */
    public function tests_getLocale_method()
    {
        $translator = m::mock(Translator::class);
        $translator->shouldReceive('getLocale')->once()->andReturn('result');
        $this->app->bind(Translator::class, fn () => $translator);
        $this->assertSame('result', FjordApp::getLocale());
    }

    /** @test */
    public function tests_isLocale_method()
    {
        $translator = m::mock(Translator::class)->makePartial();
        $translator->shouldReceive('isLocale')->withArgs(['en'])->once()->andReturn(true);
        $translator->shouldReceive('isLocale')->withArgs(['de'])->once()->andReturn(false);
        $this->app->bind(Translator::class, fn () => $translator);
        $this->assertTrue(FjordApp::isLocale('en'));
        $this->assertFalse(FjordApp::isLocale('de'));
    }
}
