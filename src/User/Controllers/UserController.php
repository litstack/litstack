<?php

namespace Ignite\User\Controllers;

use Ignite\Support\IndexTable;
use Ignite\User\Requests\UserReadRequest;
use Illuminate\Http\Request;
use Lit\Models\User;

class UserController
{
    /**
     * Create new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = lit()->config('user.user_index');
    }

    /**
     * Show user index.
     *
     * @param  UserReadRequest $request
     * @return void
     */
    public function showIndex(UserReadRequest $request)
    {
        $config = $this->config->get(
            'sortBy',
            'sortByDefault',
            'perPage',
            'index',
            'filter'
        );

        return view('litstack::app')->withComponent('lit-users')
            ->withTitle('Users')
            ->withProps([
                'config' => $config,
            ]);
    }

    /**
     * Fetch index.
     *
     * @param  Request $request
     * @return array
     */
    public function fetchIndex(UserReadRequest $request)
    {
        $query = $this->config->index_query
            ->with('ordered_roles');

        return IndexTable::query($query)
            ->request($request)
            ->search($this->config->search)
            ->get();
    }
}
