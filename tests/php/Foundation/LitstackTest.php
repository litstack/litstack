<?php

namespace Tests\Foundation;

use Ignite\Application\Application;
use Ignite\Foundation\Litstack;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\Traits\ActingAsLitUserMock;

class LitstackTest extends BackendTestCase
{
    use ActingAsLitUserMock;

    /** @test */
    public function test_isAppTranslatable_method()
    {
        $lit = app(Litstack::class);
        config()->set('translatable.locales', ['en']);
        $this->assertFalse($lit->isAppTranslatable());

        // Translatable when two locales exist.
        config()->set('translatable.locales', ['en', 'de']);
        $this->assertTrue($lit->isAppTranslatable());
    }

    /** @test */
    public function test_url_method()
    {
        $lit = app(Litstack::class);
        config()->set('lit.route_prefix', 'foo');
        $this->assertSame('/foo/bar', $lit->url('bar'));
    }

    /** @test */
    public function test_route_method()
    {
        $lit = app(Litstack::class);
        URL::partialMock()->shouldReceive('route')->withArgs(['lit.bar', [], true])->andReturn('/foo');
        $this->assertSame('/foo', $lit->route('bar'));
    }

    /** @test */
    public function test_trans_method()
    {
        $lit = app(Litstack::class);
        Lang::partialMock()->shouldReceive('trans')->withArgs(['bar', ['baz'], 'es'])->andReturn('foo');
        $this->assertSame('foo', $lit->trans('bar', ['baz'], 'es'));
    }

    /** @test */
    public function test_trans_short_method()
    {
        $lit = app(Litstack::class);
        Lang::partialMock()->shouldReceive('trans')->withArgs(['bar', ['baz'], 'es'])->andReturn('foo');
        $this->assertSame('foo', $lit->__('bar', ['baz'], 'es'));
    }

    /** @test */
    public function test_transChoice_method()
    {
        $lit = app(Litstack::class);
        Lang::partialMock()->shouldReceive('choice')->withArgs(['bar', 2, ['baz'], 'es'])->andReturn('foo');
        $this->assertSame('foo', $lit->transChoice('bar', 2, ['baz'], 'es'));
    }

    /** @test */
    public function test_getLocale_method()
    {
        $lit = app(Litstack::class);
        Lang::partialMock()->shouldReceive('getLocale')->andReturn('foo');
        $this->assertSame('foo', $lit->getLocale());
    }

    /** @test */
    public function test_config_method()
    {
        $lit = app(Litstack::class);
        Config::partialMock()->shouldReceive('get')->andReturn('foo');
        $this->assertSame('foo', $lit->config('bar'));
    }

    /** @test */
    public function test_user_method()
    {
        $lit = app(Litstack::class);
        $guard = m::mock('guard');
        $guard->shouldReceive('user')->andReturn('foo');
        Auth::partialMock()->shouldReceive('guard')->andReturn($guard);
        $this->assertSame('foo', $lit->user());
    }

    /** @test */
    public function test_style_method()
    {
        $app = m::mock(Application::class);
        $lit = app(Litstack::class);
        $lit->bindApp($app);

        $app->shouldReceive('style')->withArgs(['path']);

        $lit->style('path');
    }

    /** @test */
    public function test_script_method()
    {
        $app = m::mock(Application::class);
        $lit = app(Litstack::class);
        $lit->bindApp($app);

        $app->shouldReceive('script')->withArgs(['path']);

        $lit->script('path');
    }
}
