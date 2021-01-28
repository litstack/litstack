<?php

namespace Ignite\Info\Controllers;

use Ignite\Page\Page;

class InfoController
{
    /**
     * Show system info.
     *
     * @return Page
     */
    public function showInfo(): Page
    {
        $page = new Page;

        $page->view('litstack::info');

        return $page->title('System Info');
    }
}
