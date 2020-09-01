<?php

namespace Ignite\User\Controllers;

use Ignite\Support\IndexTable;
use Illuminate\Http\Request;

class ProfileController
{
    /**
     * Fetch index.
     *
     * @param Request $request
     *
     * @return array
     */
    public function sessions(Request $request)
    {
        return IndexTable::query(lit_user()->sessions()->getQuery())
            ->request($request)
            ->get();
    }
}
