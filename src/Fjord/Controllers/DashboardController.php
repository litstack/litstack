<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = require fjord_resource_path('dashboard.php');

        return view('fjord::vue')->withComponent('dashboard')
            ->withTitle('Dashboard')
            ->withProps([
                'components' => $dashboard['components'] ?? []
            ]);
    }
}
