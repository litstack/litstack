<?php

namespace AwStudio\Fjord\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\Repeatable;


class FjordRepeatableController extends Controller
{
    public function store(Request $request)
    {
        $repeatable = new Repeatable();
        $repeatable->save();

        $repeatable->update($request->all());

        return eloquentJs($repeatable, Repeatable::class);
    }

    public function update(Request $request, $id)
    {
        $repeatable = Repeatable::findOrFail($id);

        $repeatable->update($request->all());

        return $repeatable;
    }
}
