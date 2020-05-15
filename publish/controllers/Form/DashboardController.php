<?php

namespace App\Http\Controllers\Fjord;

use Fjord\Fjord\Controllers\DashboardController as Controller;

class DashboardController extends Controller
{
    /**
     * Get dashboard view.
     *
     * @return View fjord::app
     */
    public function __invoke()
    {
        return view('fjord::app')->withComponent('fj-dashboard');
    }
}
