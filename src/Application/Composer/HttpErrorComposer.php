<?php

namespace Fjord\Application\Composer;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Fjord\Application\Kernel\HandleViewComposer;

class HttpErrorComposer
{
    /**
     * Show Fjord error pages.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $fjordPrefix = preg_replace('#/+#', '/', "/" . config('fjord.route_prefix') . "/");
        if (!Str::startsWith(Request::getRequestUri(), $fjordPrefix)) {
            return;
        }

        // Compose Fjord error pages only when logged in.
        if (!fjord_user()) {
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
