<?php

namespace Fjord\Page\Table;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TableAction
{
    /**
     * Component name.
     *
     * @var string
     */
    protected $componentName = 'fj-table-action';

    /**
     * Vue component instance.
     *
     * @var \Fjord\Vue\Component
     */
    protected $component;

    /**
     * Action.
     *
     * @var string|array|Closure
     */
    protected $action;

    /**
     * Action title.
     *
     * @var string
     */
    protected $title;

    /**
     * Controller instance.
     *
     * @var mixed
     */
    protected $controller;

    /**
     * Create new TableAction instance.
     *
     * @param string               $title
     * @param string|array|Closure $action
     */
    public function __construct($title, $action)
    {
        $this->title = $title;
        $this->action = $action;

        $this->component = component($this->componentName)->prop('title', $title);
    }

    /**
     * Set action route prefix.
     *
     * @param  string $routePrefix
     * @return $this
     */
    public function route(string $route)
    {
        $this->component->prop('route', $route);

        return $this;
    }

    /**
     * Get action component.
     *
     * @return \Fjord\Vue\Component
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get action.
     *
     * @return string|array|Closure
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Resolve action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolve(Collection $models)
    {
        $result = $this->run($models);
        if (! $result instanceof JsonResponse) {
            return $this->defaultResponse();
        }

        return $result;
    }

    /**
     * Resolve action.
     *
     * @param  Collection $models
     * @return mixed
     */
    protected function run(Collection $models)
    {
        if ($this->isControllerAction()) {
            return $this->runController($models);
        }

        return $this->runCallable($models);
    }

    /**
     * Run the action and return the response.
     *
     * @param  Collection $models
     * @return mixed
     */
    protected function runCallable(Collection $models)
    {
        return app()->call($this->action, [
            'models' => $models,
        ]);
    }

    /**
     * Checks whether the action is a controller.
     *
     * @return bool
     */
    protected function isControllerAction()
    {
        return ! $this->action instanceof Closure;
    }

    /**
     * Run the route action and return the response.
     *
     * @param  Collection $models
     * @return mixed
     */
    protected function runController(Collection $models)
    {
        if ($method = $this->getControllerMethod()) {
            $call = [$this->getController(), $method];
        } else {
            $call = $this->getController();
        }

        return app()->call($call, ['models' => $models]);
    }

    /**
     * Get the controller instance for the route.
     *
     * @return mixed
     */
    public function getController()
    {
        if (! $this->controller) {
            $class = $this->parseControllerCallback()[0];

            $this->controller = app()->make(ltrim($class, '\\'));
        }

        return $this->controller;
    }

    /**
     * Get the controller method used for the route.
     *
     * @return string
     */
    protected function getControllerMethod()
    {
        return $this->parseControllerCallback()[1];
    }

    /**
     * Parse the controller.
     *
     * @return array
     */
    protected function parseControllerCallback()
    {
        if (is_array($this->action)) {
            return $this->action;
        }

        return Str::parseCallback($this->action);
    }

    /**
     * Returns default response.
     *
     * @return JsonResponse
     */
    public function defaultResponse()
    {
        return new JsonResponse([
            'message' => "Action [{$this->title}] executed.",
        ]);
    }
}
