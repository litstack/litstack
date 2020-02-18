<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

use AwStudio\Fjord\Form\Requests\CrudDeleteRequest;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use Illuminate\Http\Request;

trait CrudIndexDeleteAll
{
    public function deleteAll(CrudDeleteRequest $request)
    {
        $this->model::whereIn('id', $request->ids)->delete();

        $count = count($request->ids);

        return response()->json([
            'message' => "Deleted {$count} item" . ($count > 1 ? 's' : '')
        ], 200);
    }

    public function makeDeleteAllRoute()
    {
        FjordRoute::post("/{$this->titlePlural}/delete-all", self::class . "@deleteAll")
            ->name("{$this->titlePlural}.delete_all");
    }

    protected function addDeleteAllExtension()
    {
        return ['index.actions' => ['fj-crud-index-delete-all']];
    }
}
