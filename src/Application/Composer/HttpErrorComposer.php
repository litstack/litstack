<?php

namespace Fjord\Application\Composer;

use Fjord\Application\Kernel\HandleViewComposer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HttpErrorComposer
{
    /**
     * Show Fjord error pages.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $fjordPrefix = strip_slashes('/'.config('fjord.route_prefix').'/');
        if (! Str::startsWith(Request::getRequestUri(), $fjordPrefix)) {
            return;
        }

        // Compose Fjord error pages only when logged in.
        if (! fjord_user()) {
            return;
        }

        // Error code.
        $error = last(explode('::', $view->getName()));

        // Unset view data.
        $data = $view->getData();
        foreach ($data as $key => $value) {
            $view->offsetUnset($key);
        }

        // Fjord view.
        $view->setView('fjord::app')
            ->withComponent("fj-error-{$error}")
            ->withProps([]);

        with(new HandleViewComposer())->compose($view);
    }
}
