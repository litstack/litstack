<?php

namespace Lit\Http\Controllers;

use Ignite\Crud\Controllers\FormController;
use Ignite\Page\Page;

class WelcomeController extends FormController
{
    /**
     * Get welcome page.
     *
     * @return \Ignite\Page\Page
     */
    public function __invoke()
    {
        $page = new Page;

        $page->htmlTitle(ucfirst(__lit('base.hello')))->view('lit::welcome');

        return $page;
    }
}
