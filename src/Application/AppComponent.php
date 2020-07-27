<?php

namespace Fjord\Application;

use Fjord\Vue\Component;
use Fjord\Vue\Traits\StaticComponentName;
use Illuminate\Contracts\View\View;
use LogicException;

class AppComponent extends Component
{
    use StaticComponentName;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name = 'fjord-app';

    /**
     * Rendering.
     *
     * @return void
     */
    public function rendering()
    {
        $this->prop('config', collect(config('fjord')));
        $this->prop('auth', fjord_user());
        $this->prop('app-locale', fjord()->getLocale());
        $this->prop('translatable', collect([
            'language'        => app()->getLocale(),
            'languages'       => collect(config('translatable.locales')),
            'fallback_locale' => config('translatable.fallback_locale'),
        ]));
    }

    /**
     * Rendered lifecycle hook.
     *
     * @return void
     */
    public function rendered($rendered)
    {
        if ($rendered['props']->has('component')) {
            return;
        }

        throw new LogicException('Missing required view data [component] for [fjord::app]');
    }

    /**
     * Bind view data.
     *
     * @param  View $view
     * @return void
     */
    public function bindView(View $view)
    {
        $this->bind($view->getData());
    }
}
