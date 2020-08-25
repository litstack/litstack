<?php

namespace Lit\User\Controllers;

use Lit\Support\IndexTable;
use Lit\User\Models\LitUser;
use Lit\User\Requests\LitUserDeleteRequest;
use Lit\User\Requests\LitUserReadRequest;
use Illuminate\Http\Request;

class LitUserController
{
    /**
     * Create new LitUserController instance.
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
     * @param  LitUserReadRequest $request
     * @return void
     */
    public function showIndex(LitUserReadRequest $request)
    {
        $config = $this->config->get(
            'sortBy',
            'sortByDefault',
            'perPage',
            'index',
            'filter'
        );

        return view('lit::app')->withComponent('lit-users')
            ->withTitle('Users')
            ->withProps([
                'config' => $config,
            ]);
    }

    /**
     * Delete multiple users.
     *
     * @param Request $request
     *
     * @return void
     */
    public function deleteAll(LitUserDeleteRequest $request)
    {
        IndexTable::deleteSelected(LitUser::class, $request);

        return response([
            'message' => __lit('lit.deleted_all', [
                'count' => count($request->ids),
            ]),
        ]);
    }

    /**
     * Fetch index.
     *
     * @param Request $request
     *
     * @return array
     */
    public function fetchIndex(LitUserReadRequest $request)
    {
        $query = $this->config->index_query
            ->with('ordered_roles');

        return IndexTable::query($query)
            ->request($request)
            ->search($this->config->search)
            ->get();
    }
}
