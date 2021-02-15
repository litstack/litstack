<?php

namespace Tests\Chart;

use Ignite\Chart\ChartSet;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ChartSetTest extends TestCase
{
    public function setUp(): void
    {
        $this->app = new Application;
        Container::setInstance($this->app);
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function test_getLabelFromTime_method()
    {
        $chart = new ChartSet(null, fn () => null, fn () => null);
        $chart->label(fn ($time) => $time->format('m.y'));
        $time = now();
        $this->assertSame($time->format('m.y'), $chart->getLabelFromTime($time));
    }

    /** @test */
    public function test_getLabelFromTime_method_returns_default_label_when_no_resolver_is_given()
    {
        $chart = new ChartSet(null, fn () => null, fn () => null);
        $time = now();
        $this->assertSame($time->format('d.m.y'), $chart->getLabelFromTime($time));
    }

    /** @test */
    public function test_iterations_method()
    {
        $iterations = 4;

        $values = m::mock(Collection::class);
        $values->shouldReceive('map');
        $query = m::mock(new DummyQueryBuilder);
        $query->shouldReceive('where')->withArgs(['foo', 'bar'])->times($iterations)->andReturn($query);
        $query->shouldReceive('unionAll');
        $query->shouldReceive('get')->andReturn($query);
        $query->shouldReceive('pluck')->andReturn($values);
        $lit = m::mock('lit');
        $this->app['lit'] = $lit;
        $chart = new ChartSet(
            $query,
            fn ($query) => $query->where('foo', 'bar'),
            fn ($time)  => $time
        );

        $this->assertSame($chart, $chart->iterations($iterations));

        $lit->shouldReceive('getLocale');
        $chart->load(now());
    }
}

class DummyQueryBuilder
{
}
