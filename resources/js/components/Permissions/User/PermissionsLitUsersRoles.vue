<template>
    <div>
        <b-form-tag
            v-for="(role, key) in item.ordered_roles"
            @remove="removeRole(role)"
            :key="key"
            :variant="roleVariant(role)"
            class="mr-1"
            v-bind:disabled="!can('update lit-user-roles')"
        >
            {{ translateRole(role) }}
        </b-form-tag>
    </div>
</template>
<script>
export default {
    name: 'PermissionsLitUsersRoles',
    props: {
        item: {
            required: true,
            type: Object,
        },
    },
    computed: {
        role() {
            return this.item.role ? this.item.role : null;
        },
    },
    methods: {
        translateRole(role) {
            return this.$te(`roles.${role.name}`)
                ? this.__(`roles.${role.name}`).toString()
                : role.name.capitalize();
        },
        async removeRole(role) {
            try {
                let response = await axios.delete(
                    `lit-user/${this.item.id}/role/${role.id}`
                );
            } catch (e) {
                console.log(e);
                return;
            }

            this.$bvToast.toast(
                this.__('permissions.messages.role_removed', {
                    username: this.item.name,
                    role: this.translateRole(role),
                }),
                {
                    variant: 'success',
                }
            );

            this.$emit('reload');
        },
        roleVariant(role) {
            switch (role.name) {
                case 'admin':
                    return 'primary';
                    break;
                case 'user':
                    return 'secondary';
                    break;
                default:
                    return 'info';
                    break;
            }
        },
    },
};
</script>
