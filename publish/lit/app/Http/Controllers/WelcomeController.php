<?php

namespace Lit\Http\Controllers;

use Ignite\Crud\Controllers\FormController;
use Ignite\Page\Page;

class WelcomeController extends FormController
{
    public function __invoke()
    {
        $page = new Page;

        $page->view('lit::welcome');

        return $page;
    }
}
