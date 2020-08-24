<?php

namespace Lit\Application\Kernel;

use Illuminate\View\View;

class HandleViewComposer
{
    /**
     * Execute Lit kernel method handleView.
     *
     * @param  Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        app()->get(\LitApp\Kernel::class)->handleView($view);
    }
}
