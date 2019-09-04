<?php

namespace AwStudio\Fjord\Form\Controllers;

use AwStudio\Fjord\Fjord\Controllers\FjordController;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;
use AwStudio\Fjord\Form\Database\FormBlock;
use AwStudio\Fjord\Form\Database\FormField;
use AwStudio\Fjord\Form\Database\FormRelation;
use Exception;

class FormRelationsController extends FjordController
{
    public function index(Request $request)
    {
        $model = with(new $request->model_type);
        if($request->model_id) {
            $model = $request->model_type::where('id', $request->model_id)->first();
        }

        $field = $model->findFormField($request->id);

        return $field['query']->get();
    }

    public function updateHasOne(Request $request)
    {
        $model = $request->model::findOrFail($request->id);
        $model->{$request->key} = $request->value;
        $model->save();
        return $model;
    }

    public function store(Request $request)
    {
        $relation = FormRelation::where('from_model_type', $request->from_model_type)
            ->where('from_model_id', $request->from_model_id)
            ->where('to_model_type', $request->to_model_type)
            ->where('to_model_id', $request->to_model_id)
            ->exists();

        if($relation) {
            return response()->json([
                'message' => 'You have already selected this item.'
            ], 400);
        }

        $data = FormRelation::create($request->all());

        return $data;
    }

    public function delete(Request $request)
    {
        FormRelation::where('from_model_type', $request->from_model_type)
            ->where('from_model_id', $request->from_model_id)
            ->where('to_model_type', $request->to_model_type)
            ->where('to_model_id', $request->to_model_id)
            ->delete();
    }

    public function order(Request $request)
    {
        $data = $request->data;
        $ids = $request->ids;

        if(! $data || ! $ids) {
            abort(404);
        }

        foreach($ids as $order => $id) {
            $relation = FormRelation::where('from_model_type', $data['from_model_type'])
                ->where('from_model_id', $data['from_model_id'])
                ->where('to_model_type', $data['to_model_type'])
                ->where('to_model_id', $id)
                ->first();
            $relation->order_column = $order;
            $relation->save();
        }
    }
}
