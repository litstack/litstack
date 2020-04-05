<?php

namespace Fjord\EloquentJs;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class EloquentJsController extends Controller
{
    public function destroy(Request $request, $id)
    {
        if(! $request->model) {
            abort(404);
        }

        $data = $request->model::findOrFail($id);
        $data->delete();
    }

    public function update(Request $request, $id) {
        if(! $request->model) {
            abort(404);
        }

        $data = $request->model::findOrFail($id);
        $data->update($request->data);

        return $data;
    }

    public function saveAll(Request $request) {
        if(! $request->items) {
            abort(404);
        }

        $items = [];
        foreach($request->items as $item) {
            if(array_key_exists('id', $item['data'])) {
                $data = $item['model']::findOrFail($item['data']['id']);
                $data->update($item['data']);

                if(is_translatable($data)) {
                    $data->append('translation');
                }

                $items[] = $data;
            } else {
                $data = $item['model']::create($item['data']);
                if(is_translatable($data)) {
                    $data->append('translation');
                }
                $items[] = $data;
            }
        }
        return $items;
    }
}
