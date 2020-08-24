<?php

namespace App\Http\Controllers\Lit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('lit::app')
            ->withComponent('example')
            ->withProps([
                'title' => 'Component Title',
            ]);
    }
}
