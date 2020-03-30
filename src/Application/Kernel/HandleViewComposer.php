<?php

namespace AwStudio\Fjord\Application\Kernel;

use Illuminate\View\View;

class HandleViewComposer
{
    /**
     * Execute AwStudio\Fjord\Application\Kernel method handleView.
     * 
     * @param Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        app()->get('fjord.kernel')->handleView($view);
    }
}
