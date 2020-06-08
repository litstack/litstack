<?php

namespace Fjord\User\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\IndexTable;

class ProfileController
{
    /**
     * Fetch index.
     *
     * @param Request $request
     * @return array
     */
    public function sessions(Request $request)
    {
        return IndexTable::query(fjord_user()->sessions()->getQuery())
            ->request($request)
            ->get();
    }
}
