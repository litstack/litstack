<?php

namespace FjordTest\Page;

use Fjord\Page\Table\Casts\MoneyColumn;
use Fjord\Support\Facades\FjordApp;
use PHPUnit\Framework\TestCase;

class MoneyColumnTest extends TestCase
{
    /** @test */
    public function it_formats_for_the_default_when_no_locale_is_given()
    {
        FjordApp::partialMock()->shouldReceive('getLocale')->andReturn('en_US')->once();
        $cast = new MoneyColumn('USD');

        $this->assertSame('$10.00', $cast->get(null, null, 10.00, null));
    }

    /** @test */
    public function it_formats_to_EUR_currency_by_default()
    {
        FjordApp::partialMock()->shouldReceive('getLocale');
        $cast = new MoneyColumn();

        $this->assertSame('10,00 €', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_by_the_given_currency()
    {
        FjordApp::partialMock()->shouldReceive('getLocale');
        $cast = new MoneyColumn('USD');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_for_the_locale()
    {
        FjordApp::partialMock()->shouldReceive('getLocale')->once();
        $cast = new MoneyColumn('USD', 'de_DE');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }
}
