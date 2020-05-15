<?php

namespace App\Http\Controllers\Fjord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('fjord::app')
            ->withComponent('example')
            ->withProps([
                'title' => 'Component Title'
            ]);
    }
}
