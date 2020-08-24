<?php

namespace Tests\Page;

use Lit\Page\Table\Casts\MoneyColumn;
use Lit\Support\Facades\LitApp;
use Tests\BackendTestCase;

class MoneyColumnTest extends BackendTestCase
{
    /** @test */
    public function it_formats_for_the_default_when_no_locale_is_given()
    {
        LitApp::partialMock()->shouldReceive('getLocale')->andReturn('en_US')->once();
        $cast = new MoneyColumn('USD');

        $this->assertSame('$10.00', $cast->get(null, null, 10.00, null));
    }

    /** @test */
    public function it_formats_to_EUR_currency_by_default()
    {
        LitApp::partialMock()->shouldReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn();

        $this->assertSame('10,00 €', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_by_the_given_currency()
    {
        LitApp::partialMock()->shouldReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn('USD');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_for_the_locale()
    {
        LitApp::partialMock()->shouldNotReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn('USD', 'de_DE');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }
}
