<?php

namespace AwStudio\Fjord\Form\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AwStudio\Fjord\Form\Database\FormBlock;


class FormBlockController extends Controller
{
    public function store(Request $request)
    {
        $form_block = FormBlock::create($request->all());

        return $form_block->eloquentJs();
    }

    public function update(Request $request, $id)
    {
        $repeatable = Repeatable::findOrFail($id);

        $repeatable->update($request->all());

        return $repeatable;
    }
}
