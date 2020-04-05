<?php

namespace AwStudio\Fjord\Application;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use AwStudio\Fjord\Application\Kernel\HandleViewComposer;

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
        $view->setView('fjord::app');
        $view->withComponent("fj-error-{$error}");
        $view->withProps([]);

        with(new HandleViewComposer())->compose($view);
    }
}
