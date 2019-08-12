<?php

namespace AwStudio\Fjord\Http\Controllers;

use AwStudio\Fjord\Http\Controllers\FjordController;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\ModelContent;
use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;
use Exception;

class FjordPageController extends FjordController
{
    public function show($page)
    {
        $repeatables = $this->getRepeatables($page);

        $pageContent = $this->getPageContent($page);

        return view('fjord::vue')->withComponent('page-show')
            ->withModels(array_merge($repeatables, [
                'pageContent' => $pageContent
            ]))
            ->withTitle(ucfirst($page))
            ->withProps([
                'pageName' => $page,
            ]);
    }

    protected function getRepeatables($page)
    {
        $repeatables = [];

        foreach(fjord()->getPage($page)['fields'] as $field) {

            if($field->type != 'block') {
                continue;
            }

            $repeatables[$field->id] = Repeatable::with('media', 'translations')
                 ->orderByRaw('-order_column DESC')
                 ->where('page_name', $page)
                 ->where('block_name', $field->id)
                 ->eloquentJs('get');

            $repeatables[$field->id]['field'] = $field->toArray();
        }
        return $repeatables;
    }

    protected function getPageContent($pageName)
    {
        $pageContent = [];
        foreach(fjord()->getPage($pageName)['fields'] as $key => $field) {
            if($field->type == 'block') {
                continue;
            }

            $pageContent[$key] = PageContent::firstOrCreate(
                ['page_name' => $pageName, 'field_name' => $field->id],
                ['content' => $field->default ?? null]
            );

            if($field->type == 'relation') {
                $pageContent[$key]->setFormRelation();
            }

        }

        return eloquentJs(collect($pageContent), PageContent::class);
    }
}
