<?php

namespace Tests\Auth;

use Ignite\Auth\Authentication;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Application;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    public function setUp(): void
    {
        $this->app = new Application;
        $config = m::mock('config');
        $this->app['config'] = $config;
        Container::setInstance($this->app);
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_attempts_login_using_email()
    {
        $this->app['config']->shouldReceive('get')
            ->withArgs(['lit.login.username', null])
            ->andReturn(false);
        $this->app['config']->shouldReceive('get');

        $guard = m::mock(Guard::class);
        $laravelAuth = m::mock(Factory::class);

        $laravelAuth->shouldReceive('guard')->andReturn($guard);
        $auth = new Authentication($laravelAuth);

        $guard->shouldReceive('attempt')->withArgs([
            ['email' => 'admin@admin.com', 'password' => 'secret'], [],
        ])->andReturn(false)->once();

        $auth->attempt([
            'email'    => 'admin@admin.com',
            'password' => 'secret',
        ]);
    }

    /** @test */
    public function it_attempts_using_email_or_username()
    {
        $this->app['config']->shouldReceive('get')
            ->withArgs(['lit.login.username', null])
            ->andReturn(true);
        $this->app['config']->shouldReceive('get');

        $guard = m::mock(Guard::class);
        $laravelAuth = m::mock(Factory::class);
        $laravelAuth->shouldReceive('guard')->andReturn($guard);
        $auth = new Authentication($laravelAuth);

        $guard->shouldReceive('attempt')->withArgs([
            ['email' => 'admin@admin.com', 'password' => 'secret'], false,
        ])->andReturn(false)->once();
        $guard->shouldReceive('attempt')->withArgs([
            ['username' => 'admin@admin.com', 'password' => 'secret'], false,
        ])->andReturn(false)->once();

        $auth->attempt([
            'email'    => 'admin@admin.com',
            'password' => 'secret',
        ]);
    }

    /** @test */
    public function it_calls_attempting_methods()
    {
        $this->app['config']->shouldReceive('get')
                ->withArgs(['lit.login.username', null])
                ->andReturn(false);
        $this->app['config']->shouldReceive('get');

        $guard = m::mock(Guard::class);
        $laravelAuth = m::mock(Factory::class);

        $laravelAuth->shouldReceive('guard')->andReturn($guard);
        $auth = new Authentication($laravelAuth);

        $guard->shouldReceive('attempt')->andReturn(true)->once();
        $guard->shouldReceive('user')->once()->andReturn('user');
        $guard->shouldReceive('logout')->once();

        $auth->attempting(function ($user, $parameters) {
            return false;
        });

        $attempt = $auth->attempt([
            'email'    => 'admin@admin.com',
            'password' => 'secret',
        ]);

        $this->assertFalse($attempt);
    }
}
