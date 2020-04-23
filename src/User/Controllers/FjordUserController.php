<?php

namespace Fjord\User\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\IndexTable;
use Fjord\User\Models\FjordUser;
use Fjord\Config\Traits\HasIndex;
use Illuminate\Routing\Controller;
use Fjord\User\Requests\FjordUserReadRequest;
use Fjord\User\Requests\FjordUserDeleteRequest;

class FjordUserController extends Controller
{
    /**
     * Show user index.
     *
     * @param FjordUserReadRequest $request
     * @return void
     */
    public function showIndex(FjordUserReadRequest $request)
    {
        $config = fjord()->config('user.user_index')->get(HasIndex::class);

        return view('fjord::app')->withComponent('fj-users')
            ->withTitle('Users')
            ->withProps([
                'usersCount' => FjordUser::count(),
                'config' => $config,
            ]);
    }

    /**
     * Delete multiple users.
     *
     * @param Request $request
     * @return void
     */
    public function deleteAll(FjordUserDeleteRequest $request)
    {
        IndexTable::deleteSelected(FjordUser::class, $request);

        return response([
            'message' => __f('fj.deleted_all', [
                'count' => count($request->ids)
            ])
        ]);
    }

    /**
     * Fetch index.
     *
     * @param Request $request
     * @return array
     */
    public function fetchIndex(FjordUserReadRequest $request)
    {
        return IndexTable::get(
            fjord()->config('user.user_index')->index_query,
            $request
        );
    }
}
