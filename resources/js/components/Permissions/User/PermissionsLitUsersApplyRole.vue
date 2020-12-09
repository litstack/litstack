<template>
    <b-dropdown right variant="primary" size="sm">
        <template v-slot:button-content>
            <lit-fa-icon icon="cogs" />
        </template>
        <b-dropdown-group
            :header="
                __('base.item_assign', {
                    item: __('base.role'),
                }).capitalizeAll()
            "
        >
            <b-dropdown-item
                href="#"
                v-for="(role, key) in roles"
                :key="key"
                @click="assignRole(role)"
                >{{
                    $te(`roles.${role.name}`)
                        ? __(`roles.${role.name}`).toString().capitalize()
                        : role.name.capitalize()
                }}</b-dropdown-item
            >
        </b-dropdown-group>
    </b-dropdown>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'PermissionsLitUsersApplyRole',
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            visible: false,
            message: {
                subject: null,
                text: null,
            },
        };
    },
    methods: {
        async assignRole(role) {
            let response = await axios.post(
                `lit-user/${this.item.id}/role/${role.id}`
            );

            this.$bvToast.toast(
                this.__('permissions.messages.role_assigned', {
                    username: this.item.name,
                    role: this.__(`roles.${role.name}`),
                }),
                {
                    variant: 'success',
                }
            );

            this.$emit('reload');
        },
    },
    computed: {
        ...mapGetters(['roles']),
    },
};
</script>
