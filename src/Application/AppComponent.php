<?php

namespace Ignite\Application;

use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;
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
    protected $name = 'lit-app';

    /**
     * Rendering.
     *
     * @return void
     */
    public function mounted()
    {
        $this->prop('debug', config('app.debug'));
        $this->prop('config', collect(config('lit')));
        $this->prop('auth', lit_user());
        $this->prop('app-locale', lit()->getLocale());
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

        throw new LogicException('Missing required view data [component] for [litstack::app]');
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
