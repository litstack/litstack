<?php

namespace Ignite\Page;

use Ignite\Contracts\Page\Expandable;
use Ignite\Exceptions\NotLoggedInException;
use Ignite\Page\Actions\ActionComponent;
use Ignite\Support\Vue\ButtonComponent;
use Ignite\Vue\Component;

class Page extends BasePage implements Expandable
{
    use Traits\Expandable,
        Traits\HasCharts;

    /**
     * Root Vue component.
     *
     * @var string
     */
    protected $rootComponent = 'lit-page';

    /**
     * Go back route & text.
     *
     * @var array|null
     */
    protected $back;

    /**
     * Breadcrumb array.
     *
     * @var array
     */
    protected $breadcrumb = [];

    /**
     * Navigation instance. Represents Vue component [lit-navigation] in [lit-page].
     *
     * @var Navigation
     */
    protected $navigation;

    /**
     * Header instance. Represents Vue component [lit-header] in [lit-page].
     *
     * @var Header
     */
    protected $header;

    /**
     * Html title.
     *
     * @var string
     */
    protected $htmlTitle;

    /**
     * Create new Page instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->navigation = new Navigation($this);
        $this->header = new Header($this);
    }

    /**
     * Add action to page.
     *
     * @param  string          $title
     * @param  string          $action
     * @param  Component       $wrapper
     * @return Component|mixed
     */
    public function action($title, $action, Component $wrapper = null)
    {
        if (! $wrapper) {
            $wrapper = component(ButtonComponent::class)
                ->child($title)
                ->size('sm')
                ->variant('primary')
                ->class('mb-3');
        }

        $component = new ActionComponent($action, $title, $wrapper);

        $this->bindAction($component);

        $this->component($component);

        return $wrapper;
    }

    /**
     * Bind the action to the page.
     *
     * @param  ActionComponent $component
     * @return void
     */
    public function bindAction(ActionComponent $component)
    {
        //
    }

    /**
     * Set go back route & text.
     *
     * @param  string $text
     * @param  string $route
     * @return $this
     */
    public function goBack(string $text, string $route)
    {
        $this->back = [
            'text'  => $text,
            'route' => $route,
        ];

        return $this;
    }

    /**
     * Set breadcrumb array.
     *
     * @param  array $breadcrumb
     * @return $this
     */
    public function breadcrumb(array $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * Get breadcrumb.
     *
     * @return void
     */
    public function getBreadcrumb()
    {
        return $this->breadcrumb;
    }

    /**
     * Get header instance.
     *
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set page title.
     *
     * @param  string $title
     * @return string
     */
    public function title(string $title)
    {
        $this->header->setTitle($title);

        return $this;
    }

    /**
     * Get page title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->header->getTitle();
    }

    /**
     * Set html title.
     *
     * @param  string $title
     * @return void
     */
    public function htmlTitle($title)
    {
        $this->htmlTitle = $title;

        return $this;
    }

    /**
     * Get html title.
     *
     * @return string
     */
    public function getHtmlTitle()
    {
        return $this->htmlTitle ?? $this->getTitle();
    }

    /**
     * Get navigation slot "left".
     *
     * @return Slot
     */
    public function navigationLeft()
    {
        return $this->navigation->getLeftSlot();
    }

    /**
     * Get navigation slot "controls".
     *
     * @return Slot
     */
    public function navigationControls()
    {
        return $this->navigation->getControlsSlot();
    }

    /**
     * Get navigation slot "right".
     *
     * @return Slot
     */
    public function navigationRight()
    {
        return $this->navigation->getRightSlot();
    }

    /**
     * Get header slot "left".
     *
     * @return Slot
     */
    public function headerLeft()
    {
        return $this->header->getLeftSlot();
    }

    /**
     * Get header slot "right".
     *
     * @return Slot
     */
    public function headerRight()
    {
        return $this->header->getRightSlot();
    }

    /**
     * Render page.
     *
     * @return array
     */
    public function render(): array
    {
        if (! lit_user()) {
            throw new NotLoggedInException(static::class.' requires an authentificated lit_user to render.');
        }

        $this->bindDataToSlotViews();

        return array_merge([
            'navigation' => $this->navigation,
            'header'     => $this->header,
            'back'       => $this->back,
            'breadcrumb' => $this->breadcrumb,
        ], parent::render());
    }

    /**
     * Bind data to slot views.
     *
     * @return void
     */
    protected function bindDataToSlotViews()
    {
        foreach ([
            $this->navigationRight(),
            $this->navigationLeft(),
            $this->navigationControls(),
            $this->headerLeft(),
            $this->headerRight(),
        ] as $slot) {
            foreach ($slot->getViews() as $view) {
                $view->with($this->viewData);
            }
        }
    }

    /**
     * Format page to string.
     *
     * @return string
     */
    public function __toString()
    {
        return view('litstack::app')
            ->withComponent($this->rootComponent)
            ->withProps(array_merge([
                'page' => collect($this->render()),
            ], $this->props))
            ->withTitle($this->getHtmlTitle())
            ->render();
    }
}
