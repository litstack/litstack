<template>
    <b-col cols="12" class="d-flex justify-content-between">
        <div></div>
        <b-button variant="primary" @click="visible = true">
            <fa-icon icon="user-shield" />
            {{ __('profile.change_password').capitalizeAll() }}
        </b-button>
        <b-modal
            v-model="visible"
            :title="__('profile.change_password').capitalizeAll()"
        >
            <b-form-group
                id="input-group-1"
                :label="__('profile.current_password').capitalizeAll()"
                label-for="input-1"
            >
                <b-form-input
                    id="input-1"
                    v-model="form.current_password"
                    type="password"
                    required
                ></b-form-input>
            </b-form-group>
            <b-form-group
                id="input-group-1"
                :label="__('profile.new_password').capitalizeAll()"
                label-for="input-1"
            >
                <b-input-group class="mb-2">
                    <b-form-input
                        id="input-1"
                        v-model="form.new_password"
                        :type="showNew ? 'text' : 'password'"
                        required
                    ></b-form-input>
                    <b-input-group-append is-text>
                        <a
                            href="#"
                            :class="showNew ? 'text-primary' : 'text-secondary'"
                            @click.prepend="showNew = !showNew"
                        >
                            <fa-icon icon="eye" />
                        </a>
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
            <b-form-group
                id="input-group-1"
                :label="__('profile.new_password_confirm').capitalizeAll()"
                label-for="input-1"
            >
                <b-input-group class="mb-2">
                    <b-form-input
                        id="input-1"
                        v-model="new_password_repeat"
                        :type="showNew ? 'text' : 'password'"
                        required
                    ></b-form-input>
                    <b-input-group-append is-text>
                        <a
                            href="#"
                            :class="showNew ? 'text-primary' : 'text-secondary'"
                            @click.prepend="showNew = !showNew"
                        >
                            <fa-icon icon="eye" />
                        </a>
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button variant="primary" size="sm" class="float-right">
                        <fa-icon icon="user-shield" />
                        {{ __('profile.change_password').capitalizeAll() }}
                        <b-spinner
                            label="Loading..."
                            small
                            v-if="busy"
                        ></b-spinner>
                    </b-button>
                </div>
            </template>
        </b-modal>
    </b-col>
</template>

<script>
export default {
    name: 'ProfileSecurityChangePassword',
    props: {},
    data() {
        return {
            busy: false,
            visible: false,
            showNew: false,
            form: {
                current_password: '',
                new_password: ''
            },
            new_password_repeat: ''
        };
    }
};
</script>
