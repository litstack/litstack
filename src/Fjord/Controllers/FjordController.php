<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FjordController extends Controller
{
    public function order(Request $request)
    {
        $model = $request->model;

        $i = 1;
        foreach ($request->order as $id) {
            $row = $model::findOrFail($id);
            $row['order_column'] = $i;
            $row->save();
            $i++;
        }
        return $request->all();
    }
}
