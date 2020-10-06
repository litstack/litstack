<?php

return [
    'role_name'  => 'Rollen name',
    'operations' => [
        'lit-user-roles' => 'Benutzer Rollen',
        'lit-users'      => 'Benutzer',
    ],
    'groups' => [
        //
    ],
    'messages' => [
        'cant_remove_admin_role'  => 'Du kannst die Rolle Admin nicht von deinem Account entfernen.',
        'permission_updated'      => 'Berechtigung geändert: :group :operation',
        'added_role'              => 'Rolle {role} wurde erstellt.',
        'deleted_role'            => 'Rolle {role} wurde gelöscht.',
        'cant_delete_role'        => 'Die Rolle :role kann nicht gelöscht werden.',
        'confirm_delete_role_msg' => 'Alle Benutzer der Rolle <b>{role}</b> werden der Rolle <b>Benutzer</b> zugewiesen.',
        'all_permission_updated'  => 'Berechtigungen geändert: {group}',
        'permission_updated'      => 'Berechtigung geändert: {group} {operation}',
        'role_assigned'           => '{username} wurde die Rolle {role} zugewiesen.',
        'role_assigned'           => '{username} wurde die Rolle {role} entzogen.',
    ],
];
