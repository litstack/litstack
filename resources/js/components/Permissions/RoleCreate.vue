<template>
    <b-button variant="primary" @click="visible = true">
        <lit-fa-icon icon="plus" />
        {{ __('base.item_add', { item: __('base.role') }).capitalizeAll() }}
        <b-modal
            v-model="visible"
            :title="
                __('base.item_add', { item: __('base.role') }).capitalizeAll()
            "
        >
            <b-form-group
                :label="__('permissions.role_name').capitalizeAll()"
                label-for="name"
                :state="nameState"
            >
                <b-form-input v-model="name" id="name" trim />
                <b-form-invalid-feedback :state="nameErrorState">
                    {{ error('name') }}
                </b-form-invalid-feedback>
            </b-form-group>
            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button
                        variant="primary"
                        size="sm"
                        class="float-right"
                        @click="storeRole"
                        :disabled="!nameState"
                    >
                        <lit-fa-icon icon="user-tag" />
                        {{
                            __('base.item_create', {
                                item: __('base.role'),
                            }).capitalizeAll()
                        }}
                        <b-spinner
                            label="Loading..."
                            small
                            v-if="busy"
                        ></b-spinner>
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-button>
</template>
<script>
export default {
    name: 'RoleCreate',
    data() {
        return {
            visible: false,
            name: '',
            busy: false,
            errors: [],
        };
    },
    methods: {
        async storeRole() {
            this.busy = true;
            let response = null;
            try {
                response = await axios.post('/role', {
                    name: this.name,
                });
            } catch (e) {
                this.errors = e.response.data.errors;
                this.busy = false;
                return;
            }

            let role = response.data;

            window.location.reload();

            this.visible = false;

            this.$bvToast.toast(
                this.__('permissions.messages.added_role', {
                    role: role.name.capitalize(),
                }),
                {
                    variant: 'success',
                }
            );
            this.busy = false;
        },
        error(key) {
            if (this.errors.hasOwnProperty(key)) {
                return this.errors[key].join(', ');
            }
        },
    },
    computed: {
        nameState() {
            return this.name.length > 1;
        },
        nameErrorState() {
            return !this.errors.hasOwnProperty('name');
        },
    },
};
</script>
