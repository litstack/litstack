<?php

namespace Tests\Crud;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\CrudColumnBuilder;
use Ignite\Crud\CrudIndexTable;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithComponents;

class CrudIndexTableTest extends TestCase
{
    use InteractsWithComponents;

    public function setUp(): void
    {
        $this->setupApplication();
        $this->app['lit'] = $this->lit = m::mock('lit');
        $this->lit->shouldReceive('trans');
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_binds_action_to_config()
    {
        $config = m::mock(ConfigHandler::class);
        $config->shouldReceive('routePrefix')->andReturn('foo');
        $config->shouldReceive('bindAction')->once();
        $builder = new CrudColumnBuilder($config);
        $table = new CrudIndexTable($config, $builder);
        $table->action('foo', DummyCrudIndexTableAction::class);
    }
}

class DummyCrudIndexTableAction
{
}
