<?php

namespace AwStudio\Fjord\Http\Controllers;

use AwStudio\Fjord\Http\Controllers\FjordController;
use Illuminate\Http\Request;
use AwStudio\Fjord\Models\Content;
use AwStudio\Fjord\Models\Repeatable;
use AwStudio\Fjord\Models\PageContent;

class FjordPageController extends FjordController
{
    public function show($page)
    {
        $config = collect(array_merge([
            'page' => $page,
            'fields' => config('fjord-pages')[$page]['fields'],
            'repeatables' => config('fjord-repeatables'),
            'translatable' =>  config('fjord-pages')[$page]['translatable'],
            'languages' => config('translatable.locales'),
        ]));

        // This will sort by order column and put NULL at the end
        $repeatables = Repeatable::with('media', 'translations')
                                 ->orderByRaw('-order_column DESC')
                                 ->where('page_name', $page)
                                 ->get();

        $pageContent = PageContent::with('translations')->where('page_name', $page)->get();

        return view('fjord::vue')->withComponent('pages-show')
                                ->withTitle(ucfirst($page))
                                ->withProps([
                                    'config' => $config,
                                    'repeatables' => $repeatables,
                                    'pagecontent' => $pageContent
                                ]);
    }
}
