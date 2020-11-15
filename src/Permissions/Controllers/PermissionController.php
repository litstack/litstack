<?php

namespace Ignite\Permissions\Controllers;

use Ignite\Page\Page;
use Ignite\Page\Table\ColumnBuilder;
use Ignite\Page\Table\Table;
use Ignite\Permissions\Models\RolePermission;
use Ignite\Permissions\Requests\RolePermission\ReadRolePermissionRequest;
use Ignite\Support\IndexTable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Search keys.
     *
     * @var array
     */
    protected $search = ['name'];

    /**
     * Show index.
     *
     * @param  ReadRolePermissionRequest $request
     * @return View
     */
    public function index(ReadRolePermissionRequest $request, Page $page)
    {
        $page->title(ucfirst(__lit('base.permissions')));

        $operations = $this->getUniqueOperations();
        if ($operations->count() > 6) {
            $page->expand();
        }

        $page->navigationRight()->component('lit-role-create');

        $page->component('lit-permissions')->bind([
            'cols'             => $this->getCols(),
            'roles'            => Role::where('guard_name', config('lit.guard'))->get(),
            'operations'       => $operations,
            'role_permissions' => RolePermission::all(),
            'config'           => [
                'sortBy' => [
                    'id.desc' => __lit('crud.sort_new_to_old'),
                    'id.asc'  => __lit('crud.sort_old_to_new'),
                ],
                'sortByDefault' => 'id.desc',
            ],
        ]);

        return $page;
    }

    /**
     * Get unique operations.
     *
     * @return Collection
     */
    protected function getUniqueOperations()
    {
        $names = Permission::select('name')->pluck('name');

        return $names->map(function ($name) {
            return explode(' ', $name)[0];
        })->unique();
    }

    /**
     * Get table columns.
     *
     * @return Table
     */
    protected function getCols()
    {
        $index = new ColumnBuilder;

        $index->component('lit-index-group-name')
            ->label(ucfirst(__lit('base.group')))
            ->sortBy('permission_group');

        foreach ($this->getUniqueOperations() as $operation) {
            $index->component('lit-permissions-toggle')
                ->prop('operation', $operation)
                ->label($this->translateOperation($operation));
        }

        $index->component('lit-permissions-toggle-all')
            ->label(ucfirst(__lit('base.toggle_all')))
            ->small();

        return $index;
    }

    /**
     * Translation operation.
     *
     * @param  string $operation
     * @return string
     */
    protected function translateOperation($operation)
    {
        return ucfirst(__lit_("permissions.permission.{$operation}", $operation));
    }

    /**
     * Fetch index.
     *
     * @param  ReadRolePermissionRequest $request
     * @return array
     */
    public function fetchIndex(ReadRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->tab['id']);

        $query = Permission::select([
            '*',
            DB::raw("SUBSTRING_INDEX(name, ' ', 1) AS operation"),
            DB::raw("SUBSTRING_INDEX(name, ' ', -1) AS permission_group"),
        ])
            ->whereRaw("SUBSTRING_INDEX(name, ' ', -1) != 'lit-role-permissions'")
            ->where('guard_name', $role->guard_name);

        $data = IndexTable::query($query)
            ->request($request)
            ->search($this->search)
            ->except(['paginate'])
            ->get();

        $data['unique_items'] = $data['items']->unique('permission_group');

        $data['count'] = $data['unique_items']->count();

        // Converting Object to array for component lit-index-table.
        $data['unique_items'] = array_values($data['unique_items']->toArray());

        return $data;
    }
}
