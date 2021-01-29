<?php

namespace Tests\Browser;

use Ignite\Support\Facades\Lit;
use Lit\Models\User;
use Tests\FrontendTestCase;

/**
 * Testing that login works after installation.
 */
class LoginTest extends FrontendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        if ($this->user = User::where('email', 'test@test.com')->first()) {
            return;
        }
        $this->user = factory(User::class)->create([
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
            $browser->visit(config('lit.route_prefix'))
                ->assertSee('Login')
                ->assertPathIs(Lit::url('login'));
        });
    }

    /**
     * @test
     */
    public function it_can_login()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $browser->visit(config('lit.route_prefix'))
                ->type('email', $this->user->email)
                ->type('password', 'secret')
                ->press('.btn-primary')
                ->waitUntil('window.location.pathname != "'.Lit::url('login').'"', 15)
                // it redirects to correct route.
                ->assertPathIs(
                    rtrim(Lit::url(config('lit.default_route')), '/')
                );
        });
    }
}
