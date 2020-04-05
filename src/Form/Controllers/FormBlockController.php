<?php

namespace Fjord\Form\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Fjord\Form\Database\FormBlock;

class FormBlockController extends Controller
{
    public function store(Request $request)
    {
        $form_block = FormBlock::create($request->all());

        return $form_block->eloquentJs();
    }

    public function update(Request $request, $id)
    {
        $formBlock = FormBlock::findOrFail($id);

        $formBlock->update($request->all());

        return $formBlock;
    }
}
