<template>
    <div class="lit-permisson-toggle">
        <b-form-checkbox
            v-model="checked"
            name="check-button"
            @change="
                val => {
                    this.change(val, true);
                }
            "
            :disabled="!permission"
            :class="{ disabled: !permission }"
            switch
        />
    </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    name: 'PermissionsToggle',
    props: {
        item: {
            required: true,
            type: [Object, Array],
        },
        col: {
            type: Object,
        },
        operation: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            checked: false,
        };
    },
    beforeMount() {
        this.checked = this.roleHasPermission();

        this.$bus.$on('litPermissionsToggleAll', this.toggle);
    },
    methods: {
        async change(val, log) {
            let payload = {
                role: this.litPermissionsRole,
                permission: this.permission.id,
            };

            let response = await axios.put('role_permissions', payload);

            this.$store.commit(
                'SET_LIT_PERMISSIONS_ROLE_PERMISSIONS',
                response.data
            );
            if (!log) {
                return;
            }

            this.$bvToast.toast(
                this.__('permissions.messages.permission_updated', {
                    operation: this.$te(
                        `permissions.operations.${this.operation}`
                    )
                        ? this.__(
                              `permissions.operations.${this.operation}`
                          ).toString()
                        : this.operation.capitalize(),
                    group: this.$te(`permissions.groups.${this.group}`)
                        ? this.__(`permissions.groups.${this.group}`).toString()
                        : this.group.capitalize(),
                }),
                {
                    variant: 'success',
                }
            );
        },
        toggle({ on, group }) {
            if (this.group != group) {
                return;
            }

            if (!this.permission) {
                return;
            }

            if ((this.checked && on) || (!this.checked && !on)) {
                return;
            }

            this.checked = !this.checked;
            this.change(this.checked, false);
        },
        roleHasPermission() {
            if (!this.permission) {
                return false;
            }
            return (
                _.size(
                    _.filter(this.litPermissionsRolePermissions, {
                        role_id: this.litPermissionsRole.id,
                        permission_id: this.permission.id,
                    })
                ) > 0
            );
        },
    },
    computed: {
        ...mapGetters([
            'litPermissionsRole',
            'litPermissionsPermissions',
            'litPermissionsRolePermissions',
        ]),
        group() {
            return this.item.name
                .split(' ')
                .slice(1)
                .join(' ');
        },
        permissionName() {
            return `${this.operation} ${this.group}`;
        },
        permission() {
            for (let id in this.litPermissionsPermissions) {
                let permission = this.litPermissionsPermissions[id];
                if (permission.name == this.permissionName) {
                    return permission;
                }
            }
        },
    },
};
</script>
<style lang="scss">
.lit-permisson-toggle > .disabled {
    opacity: 0.25;
}
</style>
