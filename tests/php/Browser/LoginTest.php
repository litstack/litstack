<?php

namespace FjordTest\Browser;

use Fjord\Support\Facades\Fjord;
use Fjord\User\Models\FjordUser;
use FjordTest\FrontendTestCase;

/**
 * Testing that login works after installation.
 */
class LoginTest extends FrontendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if ($this->user = FjordUser::where('email', 'test@test.com')->first()) {
            return;
        }
        $this->user = factory(FjordUser::class)->create([
            'username' => 'test',
            'email'    => 'test@test.com',
            'password' => bcrypt('secret'),
        ]);
    }

    /**
     * @test
     */
    public function it_shows_login_page_on_route_prefix()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $browser->visit(config('fjord.route_prefix'))
                ->assertSee('Login')
                ->assertPathIs(Fjord::url('login'));
        });
    }

    /**
     * @test
     */
    public function it_can_login()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $browser->visit(config('fjord.route_prefix'))
                ->type('email', $this->user->email)
                ->type('password', 'secret')
                ->press('.btn-primary')
                ->waitUntil('window.location.pathname != "'.Fjord::url('login').'"', 15)
                // it redirects to correct route.
                ->assertPathIs(Fjord::url(config('fjord.default_route')));
        });
    }
}
