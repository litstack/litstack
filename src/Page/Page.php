<?php

namespace Fjord\Page;

use Fjord\Contracts\Page\Expandable;
use Fjord\Exceptions\NotLoggedInException;

class Page extends BasePage implements Expandable
{
    use Traits\Expandable,
        Traits\HasCharts;

    /**
     * Root Vue component.
     *
     * @var string
     */
    protected $rootComponent = 'fj-page';

    /**
     * CrudIndex container slots.
     *
     * @var array
     */
    protected $slots = [];

    /**
     * Page title.
     *
     * @var string
     */
    protected $title;

    /**
     * Go back route & text.
     *
     * @var array|null
     */
    protected $back;

    /**
     * Navigation instance.
     *
     * @var Navigation
     */
    public $navigation;

    /**
     * Create new Page instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->navigation = new Navigation;
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
            'route' => $text,
        ];

        return $this;
    }

    /**
     * Set page title.
     *
     * @param  string $title
     * @return string
     */
    public function title(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get page title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Render page.
     *
     * @return array
     */
    public function render(): array
    {
        if (! fjord_user()) {
            throw new NotLoggedInException(static::class.' requires an authentificated fjord_user.');
        }

        return array_merge([
            'slots'      => collect($this->slots),
            'title'      => $this->title,
            'navigation' => $this->navigation,
            'back'       => $this->back,
        ], parent::render());
    }

    /**
     * Format page to string.
     *
     * @return string
     */
    public function __toString()
    {
        return view('fjord::app')
            ->withComponent($this->rootComponent)
            ->withProps(array_merge([
                'page' => collect($this->render()),
            ], $this->props))
            ->withTitle($this->title)
            ->render();
    }
}
