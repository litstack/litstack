<?php

namespace App\Http\Controllers\Fjord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('fjord::app')
            ->withComponent('example')
            ->withProps([
                'title' => 'Component Title',
            ]);
    }
}
