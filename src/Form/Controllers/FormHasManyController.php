<?php

namespace Fjord\Form\Controllers;

use Fjord\Fjord\Controllers\FjordController;
use Fjord\Support\Facades\FormLoader;
use Illuminate\Http\Request;

class FormHasManyController extends FjordController
{
    public function relations(Request $request)
    {
        $model = new $request->model;
        $foreign = $request->foreign;

        $related = $model->find($request->id)->$foreign()->get();

        return $related;
    }

    public function index(Request $request)
    {
        $model = with(new $request->model);

        $fields = require $model->form_fields_path;

        $query = collect($fields['form_fields'])->flatten(1)->firstWhere('id', $request->field['id'])['model'];

        if(is_string($query)){
            $query = new $query;
        }

        return $query->get();
    }

    public function update(Request $request)
    {
        $model = with(new $request->model);

        $fields = require $model->form_fields_path;

        $foreignModel = collect($fields['form_fields'])->flatten(1)->firstWhere('id', $request->foreign)['model'];

        $foreign = $foreignModel::find($request->foreign_id);

        if($foreign[$request->foreign_key] == $request->id){
            $foreign->update([
                $request->foreign_key => null
            ]);
            return response()->json([
                'detached' => true
            ]);
        }else{
            $foreign->update([
                $request->foreign_key => $request->id
            ]);
            return response()->json([
                'attached' => true
            ]);
        }
    }
}
