<?php

namespace Fjord\Page\Slots;

use Fjord\Support\VueProp;
use Fjord\Vue\Component;
use Illuminate\Contracts\View\View;

abstract class BaseSlot extends VueProp
{
    /**
     * Component stack.
     *
     * @var array
     */
    protected $components = [];

    /**
     * View stack.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Get action compoent.
     *
     * @return Fjord\Vue\Component
     */
    abstract protected function getActionComponent();

    /**
     * Add action.
     *
     * @param  string              $action
     * @return Fjord\Vue\Component
     */
    public function action($action)
    {
        $this->getActionComponent()
            ->prop('action', $action)
            ->on('run', ActionEvent::class);
    }

    /**
     * Add component to Slot.
     *
     * @return Component
     */
    public function component($component)
    {
        $component = component($component);

        $this->components[] = $component;

        return $component;
    }

    /**
     * Add Blade View to stack.
     *
     * @param  string|View $view
     * @return View
     */
    public function view($view)
    {
        if (! $view instanceof View) {
            $view = view($view);
        }

        $this->views[] = $view;

        $this->component('fj-blade')->prop('view', $view);

        return $view;
    }

    /**
     * Get views.
     *
     * @return void
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Render slot for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'components' => collect($this->components),
        ];
    }
}
