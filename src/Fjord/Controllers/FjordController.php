<?php

namespace AwStudio\Fjord\Fjord\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FjordController extends Controller
{
    public function order(Request $request)
    {
        $model = $request->model;
        
        foreach ($request->order as $order => $id) {
            $row = $model::findOrFail($id);
            $row['order_column'] = $order;
            $row->save();
        }

        return $request->all();
    }
}
