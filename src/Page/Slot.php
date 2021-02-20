<?php

namespace Ignite\Page;

use Ignite\Contracts\Page\ActionFactory;
use Ignite\Support\VueProp;
use Ignite\Vue\Components\ButtonComponent;
use Ignite\Vue\Traits\HasVueComponents;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;

class Slot extends VueProp
{
    use HasVueComponents;

    /**
     * View stack.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Parent page.
     *
     * @var string
     */
    protected $page;

    /**
     * Action factory.
     *
     * @var string
     */
    protected $actionFactory;

    /**
     * Create new Slot instance.
     *
     * @param  BasePage           $page
     * @param  ActionFactory|void $actionFactory
     * @return void
     */
    public function __construct(BasePage $page, ActionFactory $actionFactory = null)
    {
        $this->page = $page;
        $this->actionFactory = $actionFactory;
    }

    /**
     * Add action.
     *
     * @param  string                $title
     * @param  string                $action
     * @return ButtonComponent|$this
     */
    public function action($title, $action)
    {
        if (is_null($this->actionFactory)) {
            throw new InvalidArgumentException('The slot cannot have actions.');
        }

        $component = $this->component(
            $this->actionFactory->make($title, $action)
        );

        $wrapper = $component->getProp('wrapper');

        $this->page->bindAction($component);

        return $wrapper;
    }

    /**
     * Flush slot.
     *
     * @return void
     */
    public function flush()
    {
        $this->components = [];
        $this->views = [];
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

        $this->component('lit-blade')->prop('view', $view);

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
