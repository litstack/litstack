<template>
    <b-button
        size="sm"
        variant="danger"
        v-b-modal.fj-confirm-delete-role
        :disabled="cantDeleteRoleIds.includes(tab.id)"
    >
        {{
            __('base.item_delete', {
                item: `${__('base.role')} ${tab.title}`
            })
        }}
        <b-modal
            id="fj-confirm-delete-role"
            :title="
                __('fj.confirm_delete', {
                    model: tab.title
                })
            "
        >
            <b-alert
                show
                variant="warning"
                v-html="
                    __(`fjpermissions.confirm_delete_role_msg`, {
                        role: tab.title
                    })
                "
            />
            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button
                        variant="danger"
                        size="sm"
                        class="float-right"
                        @click="deleteRole(tab)"
                    >
                        <fa-icon icon="trash" />
                        {{
                            __('base.item_delete', {
                                item: __('base.role')
                            })
                        }}
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-button>
</template>
<script>
export default {
    name: 'RoleDelete',
    props: {
        tab: {
            required: true,
            type: Object
        },
        cantDeleteRoleIds: {
            required: true,
            type: Array
        }
    },
    methods: {
        async deleteRole(role) {
            try {
                await axios.delete(`role/${role.id}`);
            } catch (e) {
                return;
            }
            this.$bvModal.hide('fj-confirm-delete-role');
            this.$emit('deleted');

            this.$bvToast.toast(
                this.__('fjpermissions.deleted_role', {
                    role: role.title
                }),
                {
                    variant: 'success'
                }
            );
        }
    }
};
</script>
