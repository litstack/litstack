<?php

namespace Fjord\Page;

use Fjord\Support\VueProp;
use Illuminate\Contracts\View\View as LaravelView;

class View extends VueProp
{
    protected $view;

    public function __construct(LaravelView $view)
    {
        $this->view = $view;
    }

    public function render()
    {
        return [
            'component',
        ];
    }
}
