<template>
    <fj-base-container>
        <fj-base-header :title="'Permissions'"></fj-base-header>
        <b-card no-body>
            <b-tabs card>
                <b-tab
                    :title="role.name.capitalize()"
                    :active="index == 0"
                    v-for="(role, index) in roles"
                    :key="index"
                >
                    <b-card-text>
                        <table class="table table-hover">
                            <thead>
                                <th>
                                    Model
                                </th>
                                <th
                                    v-for="(c,
                                    index) in role_permission_operations(role)"
                                    :key="index"
                                >
                                    {{ $t(c) }}
                                </th>
                                <th>all</th>
                            </thead>
                            <tbody :class="update.updater">
                                <tr
                                    v-for="group in role_permission_groups(
                                        role
                                    )"
                                >
                                    <th scope="row">
                                        {{ group.capitalize() }}
                                    </th>
                                    <td
                                        v-for="(c,
                                        index) in role_permission_operations(
                                            role
                                        )"
                                        :key="index"
                                    >
                                        <b-form-checkbox
                                            v-model="
                                                roles_permissions[role.name][
                                                    `${c} ${group}`
                                                ]
                                            "
                                            @input="
                                                togglePermission(role, c, group)
                                            "
                                            name="check-button"
                                            switch
                                        >
                                        </b-form-checkbox>
                                    </td>
                                    <td>
                                        <b-button
                                            variant="secondary"
                                            size="sm"
                                            @click="toggleAll(role, group)"
                                            >{{ $t('toggle_all') }}</b-button
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
    </fj-base-container>
</template>

<script>
export default {
    name: 'RolePermissions',
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
            update: {
                updater: null
            },
            roles_permissions: {},
            crud: ['Create', 'Read', 'Update', 'Delete']
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
        role_permission_groups(role) {
            let keys = Object.keys(this.roles_permissions[role.name]).map(
                key => {
                    return key.split(' ')[1];
                }
            );

            return _.uniq(keys);
        },
        role_permission_operations(role) {
            let keys = Object.keys(this.roles_permissions[role.name]).map(
                key => {
                    return key.split(' ')[0];
                }
            );

            return _.uniq(keys);
        },
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
        async togglePermission(role, operation, group) {
            let permission_name = `${operation} ${group}`;

            let permission = _.find(this.permissions, [
                'name',
                permission_name
            ]);

            let payload = {
                role,
                permission
            };
            let respose = await axios.put('/role_permissions', payload);

            this.$bvToast.toast(
                this.$t('permission_updated', {
                    operation: this.$t(operation),
                    group: group.capitalize()
                }),
                {
                    variant: 'success'
                }
            );
        },
        toggleAll(role, group) {
            for (
                var i = 0;
                i < this.role_permission_operations(role).length;
                i++
            ) {
                let operation = this.role_permission_operations(role)[i];

                let key = `${operation} ${group}`;
                this.$set(
                    this.roles_permissions[role.name],
                    key,
                    !this.roles_permissions[role.name][key]
                );
                this.$set(this.update, 'updater', key);
            }
        }
    }
};
</script>
