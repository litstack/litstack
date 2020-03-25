<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = require fjord_resource_path('dashboard.php');

        return view('fjord::app')->withComponent('fj-dashboard')
            ->withTitle('Dashboard')
            ->withProps([
                'components' => $dashboard['components'] ?? []
            ]);
    }
}
