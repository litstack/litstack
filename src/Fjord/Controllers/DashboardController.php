<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('fjord::vue')->withComponent('dashboard')
            ->withTitle('Dashboard')
            ->withProps([
            ]);
    }
}
