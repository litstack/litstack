<template>
    <fj-container>
        <fj-header :title="'Role-Permissions'"></fj-header>
        <div class="card">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td></td>
                            <td v-for="role in roles">
                                {{ role.name }}
                            </td>
                        </tr>
                    </thead>
                    <tr v-for="permission in permissions">
                        <td>{{ permission.name }}</td>
                        <td v-for="role in roles">
                            <b-form-checkbox
                                v-model="
                                    roles_permissions[role.name][
                                        permission.name
                                    ]
                                "
                                @input="togglePermission(role, permission)"
                                name="check-button"
                                switch
                            >
                            </b-form-checkbox>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </fj-container>
</template>

<script>
export default {
    name: 'RolesPermissions',
    props: {
        roles: {
            type: Array
        },
        permissions: {
            type: Array
        },
        role_permissions: {
            type: Array
        }
    },
    data() {
        return {
            roles_permissions: {}
        };
    },
    beforeMount() {
        for (var i = 0; i < this.roles.length; i++) {
            let role = this.roles[i];
            this.roles_permissions[role.name] = {};
            for (var p = 0; p < this.permissions.length; p++) {
                let permission = this.permissions[p];
                this.roles_permissions[role.name][
                    permission.name
                ] = this.roleHasPermission(role, permission);
            }
        }
    },
    methods: {
        roleHasPermission(role, permission) {
            return (
                _.size(
                    _.filter(this.role_permissions, {
                        role_id: role.id,
                        permission_id: permission.id
                    })
                ) > 0
            );
        },
        async togglePermission(role, permission) {
            let payload = {
                role,
                permission
            };
            let respose = await axios.put('/role_permissions', payload);
        }
    }
};
</script>
