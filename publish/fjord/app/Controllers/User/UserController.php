<?php

namespace FjordApp\Controllers\User;

use Fjord\User\Models\FjordUser;
use Illuminate\Database\Eloquent\Builder;
use Fjord\Crud\Controllers\CrudController;
use Fjord\Crud\Requests\CrudDeleteRequest;

class UserController extends CrudController
{
    /**
     * Crud model class name.
     *
     * @var string
     */
    protected $model = FjordUser::class;

    /**
     * Delete all.
     *
     * @param CrudDeleteRequest $request
     * @return void
     */
    public function destroyAll(CrudDeleteRequest $request)
    {
        if (!is_array($request->ids)) {
            abort(404);
        }

        $ids = $request->ids;

        if (in_array(fjord_user()->id, $ids)) {
            return response()->json([
                'message' => 'You cannot delete yourself.'
            ], 405);
        }

        $this->delete($this->query()->whereIn('id', $ids));

        return response()->json([
            'message' => __f_choice('messages.deleted_items', count($request->ids))
        ], 200);
    }

    /**
     * Authorize request for permission operation and authenticated fjord-user.
     * Operations: create, read, update, delete
     *
     * @param FjordUser $user
     * @param string $operation
     * @param string $id
     * @return boolean
     */
    public function authorize(FjordUser $user, string $operation, $id = null): bool
    {
        if ($operation == 'update') {
            return $user->id == $id;
        }

        if ($operation == 'delete' && $user->id == $id) {
            return false;
        }

        return $user->can("{$operation} fjord-users");
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
