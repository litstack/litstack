<?php

namespace Fjord\Application\Kernel;

use Illuminate\View\View;

class HandleViewComposer
{
    /**
     * Execute Fjord kernel method handleView.
     * 
     * @param Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        app()->get('fjord.kernel')->handleView($view);
    }
}
