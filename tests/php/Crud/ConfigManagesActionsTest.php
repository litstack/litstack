<?php

namespace Tests\Crud;

use Ignite\Crud\Config\Concerns\ManagesActions;
use Ignite\Crud\Config\Concerns\ManagesPermissions;
use Ignite\Crud\Requests\CrudDeleteRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Page\Actions\ActionComponent;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ConfigManagesActionsTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_authorizes_action()
    {
        $app = new Application;
        Facade::setFacadeApplication($app);
        $app['config'] = m::mock('config');
        $app['config']->shouldReceive('get')->andReturn('foo');
        $user = m::mock(Authorizable::class);
        $guard = m::mock('guard');
        $guard->shouldReceive('user')->andReturn($user);
        $app['auth'] = m::mock('auth');
        $app['auth']->shouldReceive('guard')->andReturn($guard);
        Container::setInstance($app);

        $config = new ManagesActionsConfig();

        $action = new ActionComponent(ActionThatNeedsReadPermission::class, 'foo');
        $config->bindAction($action);
        $this->assertTrue($action->check());

        $action = new ActionComponent(ActionThatNeedsDeletePermission::class, 'foo');
        $config->bindAction($action);
        $this->assertFalse($action->check());
    }
}

class ActionThatNeedsReadPermission
{
    public function run(CrudReadRequest $request)
    {
    }
}

class ActionThatNeedsDeletePermission
{
    public function run(CrudDeleteRequest $request)
    {
    }
}

class ManagesActionsConfig
{
    use ManagesActions,
        ManagesPermissions;

    public $model;

    public $permissions = [
        'create' => true,
        'read'   => true,
        'update' => true,
        'delete' => false,
    ];

    public function get()
    {
        return $this;
    }

    public function permissions(): array
    {
        $this->permissions;
    }
}
