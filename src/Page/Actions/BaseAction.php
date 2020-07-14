<?php

namespace Fjord\Page\Actions;

use Fjord\Contracts\Page\ActionFactory;
use Fjord\Page\RunActionEvent;

abstract class BaseAction implements ActionFactory
{
    /**
     * Create component instance.
     *
     * @return mixed
     */
    abstract protected function createComponent();

    /**
     * Get's prepared component.
     *
     * @param  string $title
     * @param  string $action
     * @return mixed
     */
    protected function prepared($title, $action)
    {
        return $this->createComponent()
            ->prop('eventData', ['action' => $action])
            ->on('click', RunActionEvent::class)
            ->content($title);
    }
}
