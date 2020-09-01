<?php

namespace Tests\Page;

use Ignite\Page\Table\Casts\MoneyColumn;
use Ignite\Support\Facades\Lit;
use Tests\BackendTestCase;

class MoneyColumnTest extends BackendTestCase
{
    /** @test */
    public function it_formats_for_the_default_when_no_locale_is_given()
    {
        Lit::partialMock()->shouldReceive('getLocale')->andReturn('en_US')->once();
        $cast = new MoneyColumn('USD');

        $this->assertSame('$10.00', $cast->get(null, null, 10.00, null));
    }

    /** @test */
    public function it_formats_to_EUR_currency_by_default()
    {
        Lit::partialMock()->shouldReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn();

        $this->assertSame('10,00 €', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_by_the_given_currency()
    {
        Lit::partialMock()->shouldReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn('USD');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }

    /** @test */
    public function it_formats_for_the_locale()
    {
        Lit::partialMock()->shouldNotReceive('getLocale')->andReturn('de_DE');
        $cast = new MoneyColumn('USD', 'de_DE');

        $this->assertSame('10,00 $', str_replace("\xc2\xa0", ' ', $cast->get(null, null, 10.00, null)));
    }
}
