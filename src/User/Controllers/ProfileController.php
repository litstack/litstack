<?php

namespace Fjord\User\Controllers;

use Fjord\Support\IndexTable;
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
        return IndexTable::query(fjord_user()->sessions()->getQuery())
            ->request($request)
            ->get();
    }
}
