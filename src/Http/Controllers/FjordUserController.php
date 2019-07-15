<?php

namespace AwStudio\Fjord\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FjordUserController extends Controller
{
    public function index()
    {
        return view('fjord::app')->withTitle('Users');
    }
}
