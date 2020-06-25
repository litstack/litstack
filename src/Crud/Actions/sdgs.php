<?php

namespace Fjord\Crud\Actions;

class ApiResolverAction
{
    /**
     * Resolve api call by action.
     *
     * @param 
     * @param string $action
     * @param string $type
     * @return mixed
     */
    public function resolve($controller, $id, $form_type, $field_id, $action, $type)
    {
        if (!$this->hasAction($action)) {
            abort(404);
        }

        return app()->call([$this->makeAction($action, $controller), 'execute'], [
            'id' => $id,
            'form_type' => $form_type,
            'field_id' => $field_id,
            'type' => $type,
        ]);
    }

    /**
     * Determine if action exists.
     *
     * @param string $name
     * @return boolean
     */
    public function hasAction($name)
    {
        return array_key_exists($name, $this->actions);
    }

    /**
     * Create action instance.
     *
     * @param string $name
     * @param mixed $controller
     * @return BaseApiAction
     */
    protected function makeAction($name, $controller)
    {
        return new $this->actions[$name]($controller, $controller->getConfig());
    }
}
