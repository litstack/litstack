<?php

return [
    'role_name'  => 'role name',
    'operations' => [
        'lit-user-roles' => 'User Roles',
        'lit-users'      => 'Users',
    ],
    'groups' => [
        //
    ],
    'messages' => [
        'cant_remove_admin_role'  => 'You cannot remove the Admin role from your account.',
        'added_role'              => 'Created Role {role}.',
        'deleted_role'            => 'Deleted Role {role}.',
        'cant_delete_role'        => 'The Role {role} cannot be deletet.',
        'confirm_delete_role_msg' => 'All users of the role <b>{role}</b> are assigned to the role <b>User</b>.',
        'all_permission_updated'  => 'Permissions updated: {group}',
        'permission_updated'      => 'Permission updated: {operation} {group}',
        'role_assigned'           => '{username} was assigned the role {role}.',
        'role_removed'            => 'Role {role} was removed from {username}.',
    ],
];
