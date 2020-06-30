<?php

namespace FjordTest\Browser;

use FjordTest\FrontendTestCase;
use FjordTest\Traits\CreateFjordUsers;

class PublishesTest extends FrontendTestCase
{
    use CreateFjordUsers;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_form_settings_config()
    {
        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config('form.collections.settings')->route_prefix
            );
            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Settings');
        });
    }

    /** @test */
    public function test_form_home_config()
    {
        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config('form.pages.home')->route_prefix
            );

            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Home');
        });
    }
}
