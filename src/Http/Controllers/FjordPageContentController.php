<?php

namespace AwStudio\Fjord\Http\Controllers;

use AwStudio\Fjord\Http\Controllers\FjordController;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\PageContent;

class FjordPageContentController extends FjordController
{
    public function update(Request $request, $page, $field_name)
    {
        $pageContent = PageContent::updateOrCreate([
            'page_name' => $page,
            'field_name' => $field_name
        ], $request->all());

        return $pageContent;
    }
}
