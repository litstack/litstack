<?php

namespace Tests\Crud;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\CrudColumnBuilder;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class CrudColumnBuilderTest extends TestCase
{
    public function setUp(): void
    {
        //
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_binds_single_action_to_config()
    {
        $config = m::mock(ConfigHandler::class);
        $config->shouldReceive('bindAction')->once();
        $builder = new CrudColumnBuilder($config);
        $builder->action('foo', CrudColumnBuilderTestAction::class);
    }

    /** @test */
    public function it_binds_multiple_actions_to_config()
    {
        $config = m::mock(ConfigHandler::class);
        $config->shouldReceive('bindAction')->twice();
        $builder = new CrudColumnBuilder($config);
        $builder->actions([
            'foo' => CrudColumnBuilderTestAction::class,
            'bar' => CrudColumnBuilderTestAction::class,
        ]);
    }
}

class CrudColumnBuilderTestAction
{
    public function run()
    {
    }
}
