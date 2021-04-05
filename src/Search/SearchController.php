<?php

namespace Ignite\Search;

use Ignite\Support\Facades\Config;
use Illuminate\Http\Request;
use Lit\Config\SearchConfig;

class SearchController
{
    /**
     * Handle search request.
     *
     * @param  Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $search = Config::get(SearchConfig::class)->main;

        return $search->search(
            $request->get('query')
        );
    }
}
