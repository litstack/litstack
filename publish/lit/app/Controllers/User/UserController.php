<?php

namespace LitApp\Controllers\User;

use Lit\Crud\Controllers\CrudController;
use Lit\Crud\Requests\CrudDeleteRequest;
use Lit\User\Models\LitUser;
use Illuminate\Database\Eloquent\Builder;

class UserController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = LitUser::class;

    /**
     * Delete all.
     *
     * @param CrudDeleteRequest $request
     *
     * @return void
     */
    public function destroyAll(CrudDeleteRequest $request)
    {
        if (! is_array($request->ids)) {
            abort(404);
        }

        $ids = $request->ids;

        if (in_array(lit_user()->id, $ids)) {
            return response()->json([
                'message' => 'You cannot delete yourself.',
            ], 405);
        }

        $this->delete($this->query()->whereIn('id', $ids));

        return response()->json([
            'message' => __lit_choice('messages.deleted_items', count($request->ids)),
        ], 200);
    }

    /**
     * Authorize request for permission operation and authenticated lit-user.
     * Operations: create, read, update, delete.
     *
     * @param LitUser $user
     * @param string    $operation
     * @param string    $id
     *
     * @return bool
     */
    public function authorize(LitUser $user, string $operation, $id = null): bool
    {
        if ($operation == 'update') {
            return $user->id == $id;
        }

        if ($operation == 'delete' && $user->id == $id) {
            return false;
        }

        return $user->can("{$operation} lit-users");
    }

    /**
     * Initial query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model::query();
    }
}
