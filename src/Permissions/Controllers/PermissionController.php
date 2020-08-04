<?php

namespace Fjord\Permissions\Controllers;

use Fjord\Page\Table\ColumnBuilder;
use Fjord\Page\Table\Table;
use Fjord\Permissions\Models\RolePermission;
use Fjord\Permissions\Requests\RolePermission\ReadRolePermissionRequest;
use Fjord\Support\IndexTable;
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
    public function index(ReadRolePermissionRequest $request)
    {
        $config = [
            'sortBy' => [
                'id.desc' => __f('fj.sort_new_to_old'),
                'id.asc'  => __f('fj.sort_old_to_new'),
            ],
            'sortByDefault' => 'id.desc',
        ];

        return view('fjord::app')->withComponent('fj-permissions')
            ->withTitle('Permissions')
            ->withProps([
                'cols'             => $this->getCols(),
                'roles'            => Role::all(),
                'operations'       => $this->getUniqueOperations(),
                'role_permissions' => RolePermission::all(),
                'config'           => $config,
            ]);
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

        $index->component('fj-index-group-name')
            ->label(ucfirst(__f('base.group')))
            ->sortBy('permission_group');

        foreach ($this->getUniqueOperations() as $operation) {
            $index->component('fj-permissions-toggle')
                ->prop('operation', $operation)
                ->label(ucfirst(__f("base.{$operation}")));
        }

        $index->component('fj-permissions-toggle-all')
            ->label(ucfirst(__f('fj.toggle_all')))
            ->small();

        return $index;
    }

    /**
     * Fetch index.
     *
     * @param  ReadRolePermissionRequest $request
     * @return void
     */
    public function fetchIndex(ReadRolePermissionRequest $request)
    {
        $query = Permission::select([
            '*',
            DB::raw("SUBSTRING_INDEX(name, ' ', 1) AS operation"),
            DB::raw("SUBSTRING_INDEX(name, ' ', -1) AS permission_group"),
        ])->whereRaw("SUBSTRING_INDEX(name, ' ', -1) != 'fjord-role-permissions'");

        $data = IndexTable::query($query)
            ->request($request)
            ->search($this->search)
            ->except(['paginate'])
            ->get();

        $data['unique_items'] = $data['items']->unique('permission_group');

        $data['count'] = $data['unique_items']->count();

        // Converting Object to array for component fj-index-table.
        $data['unique_items'] = array_values($data['unique_items']->toArray());

        return $data;
    }
}
