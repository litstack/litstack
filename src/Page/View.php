<?php

namespace Ignite\Page;

use Ignite\Support\VueProp;
use Illuminate\Contracts\View\View as LaravelView;

class View extends VueProp
{
    protected $view;

    public function __construct(LaravelView $view)
    {
        $this->view = $view;
    }

    public function render(): array
    {
        return [
            'component',
        ];
    }
}
