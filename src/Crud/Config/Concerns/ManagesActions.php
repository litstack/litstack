<?php

namespace Ignite\Crud\Config\Concerns;

use Ignite\Crud\RunCrudActionEvent;
use Ignite\Page\Actions\ActionComponent;

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
            ->addEventData($this->getActionEventData());
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
