<?php

namespace Ignite\Application\Composer;

use Ignite\Application\Kernel\HandleViewComposer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HttpErrorComposer
{
    /**
     * Show Lit error pages.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $litPrefix = strip_slashes('/'.config('lit.route_prefix').'/');
        if (! Str::startsWith(Request::getRequestUri(), $litPrefix)) {
            return;
        }

        // Compose Lit error pages only when logged in and user has access to
        // litstack.
        if (! lit()->authorized()) {
            return;
        }

        // Error code.
        $error = last(explode('::', $view->getName()));

        // Unset view data.
        $data = $view->getData();
        foreach ($data as $key => $value) {
            $view->offsetUnset($key);
        }

        // Lit view.
        $view->setView('litstack::app')
            ->withComponent("lit-error-{$error}")
            ->withProps([]);

        with(new HandleViewComposer())->compose($view);
    }
}
