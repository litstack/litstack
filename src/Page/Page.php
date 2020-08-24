<?php

namespace Lit\Page;

use Lit\Contracts\Page\Expandable;
use Lit\Exceptions\NotLoggedInException;

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
     * Resolve action component.
     *
     * @param  \Lit\Vue\Component $component
     * @return void
     */
    public function resolveAction($component)
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
        return view('lit::app')
            ->withComponent($this->rootComponent)
            ->withProps(array_merge([
                'page' => collect($this->render()),
            ], $this->props))
            ->withTitle($this->getTitle())
            ->render();
    }
}
