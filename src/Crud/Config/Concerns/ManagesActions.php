<?php

namespace Ignite\Crud\Config\Concerns;

use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudDeleteRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Ignite\Crud\RunCrudActionEvent;
use Ignite\Page\Actions\ActionComponent;
use Illuminate\Contracts\Auth\Access\Authorizable;
use ReflectionClass;
use ReflectionParameter;
use ReflectionUnionType;

trait ManagesActions
{
    /**
     * Bind an action to the config.
     *
     * @param  ActionComponent $action
     * @return void
     */
    public function bindAction(ActionComponent $action)
    {
        $action->setEventHandler(RunCrudActionEvent::class)
            ->addEventData($this->getActionEventData())
            ->authorize(function ($user) use ($action) {
                return $this->canUserCallAction($user, $action);
            });
    }

    /**
     * Determine if user can user call action.
     *
     * @param  Authorizable    $user
     * @param  ActionComponent $action
     * @return bool
     */
    protected function canUserCallAction(Authorizable $user, ActionComponent $action)
    {
        $reflector = new ReflectionClass($action->getNamespace());

        if (! $method = $reflector->getMethod('run')) {
            return true;
        }

        $parameters = $method->getParameters();

        return collect($parameters)
            ->filter(function (ReflectionParameter $parameter) {
                $types = $parameter instanceof ReflectionUnionType
                    ? $parameter->getTypes()
                    : [$parameter->getType()];

                $requests = [
                    CrudCreateRequest::class => 'create',
                    CrudReadRequest::class   => 'read',
                    CrudUpdateRequest::class => 'update',
                    CrudDeleteRequest::class => 'delete',
                ];

                foreach ($types as $type) {
                    if (! array_key_exists($name = $type->getName(), $requests)) {
                        continue;
                    }

                    if (! $this->can($requests[$name])) {
                        return false;
                    }
                }

                return true;
            })
            ->count() == count($parameters);
    }

    /**
     * Get action event data..
     *
     * @return array
     */
    protected function getActionEventData(): array
    {
        return [
            'config' => static::class,
            'model'  => $this->model,
        ];
    }
}
