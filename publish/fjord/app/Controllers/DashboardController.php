<?php

namespace FjordApp\Controllers;

use Illuminate\Http\Request;

class DashboardController
{
    /**
     * Dashboard.
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        return view('fjord::app')
            ->withComponent('example')
            ->withTitle('Dashboard')
            ->withProps([
                'title' => 'Dashboard'
            ]);
    }
}
