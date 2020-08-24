<?php

namespace Lit\Application\Composer;

use Lit\Application\Kernel\HandleViewComposer;
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

        // Compose Lit error pages only when logged in.
        if (! lit_user()) {
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
        $view->setView('lit::app')
            ->withComponent("lit-error-{$error}")
            ->withProps([]);

        with(new HandleViewComposer())->compose($view);
    }
}
