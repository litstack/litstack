<template>
    <div class="d-inline-block">
        <b-button size="sm" variant="primary" @click="visible = !visible">
            <fa-icon icon="plus" />
            {{ $t('fj.add_model', { model: 'Fjord ' + $t('fj.user') }) }}
        </b-button>
        <b-modal
            v-model="visible"
            :title="$t('fj.add_model', { model: 'Fjord ' + $t('fj.user') })"
        >
            <b-form-group :label="$t('fj.enter_username')" label-for="username">
                <b-form-input
                    id="username"
                    v-model="user.name"
                    trim
                ></b-form-input>
                <b-form-invalid-feedback :state="usernameState">
                    {{ error('name') }}
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group
                :label="$t('fj.enter_email')"
                label-for="email"
                :state="emailState"
            >
                <b-form-input
                    id="email"
                    v-model="user.email"
                    trim
                ></b-form-input>
                <b-form-invalid-feedback :state="emailState">
                    {{ error('email') }}
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group
                :label="$t('fj.enter_password')"
                label-for="password"
                :state="passwordState"
            >
                <b-input-group class="mt-3">
                    <b-form-input
                        id="password"
                        v-model="user.password"
                        trim
                    ></b-form-input>
                    <b-input-group-append>
                        <b-button
                            variant="outline-secondary"
                            @click="makePassword"
                        >
                            <fa-icon icon="sync" />
                        </b-button>
                    </b-input-group-append>
                </b-input-group>

                <b-progress
                    class="mt-2"
                    height="2px"
                    :value="score"
                    :max="4"
                    :variant="variant"
                ></b-progress>
                <b-form-invalid-feedback :state="passwordState">
                    {{ error('password') }}
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-checkbox
                v-model="user.sendResetLink"
                name="check-button"
                switch
            >
                {{ $t('fj.user_reset_link') }}
            </b-form-checkbox>
            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button
                        variant="primary"
                        size="sm"
                        class="float-right"
                        @click="storeFjordUser"
                        :disabled="
                            !passwordState || !emailState || !usernameState
                        "
                    >
                        <fa-icon icon="user" />
                        {{
                            $t(`fj.create_model`, {
                                model: 'Fjord ' + $t('fj.user'),
                            })
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
    </div>
</template>

<script>
export default {
    name: 'UserCreate',
    data() {
        return {
            visible: false,
            user: {
                name: '',
                email: '',
                password: this.keygen(20),
                sendResetLink: true,
            },
            errors: [],
            busy: false,
        };
    },
    methods: {
        async storeFjordUser() {
            this.busy = true;
            try {
                const { data } = await axios.post('/fjord/register', this.user);

                this.$emit('userCreated', data);

                this.visible = false;
                this.init();
                this.$bvToast.toast(
                    this.$t('fj.model_saved', { model: 'Fjord User' }),
                    {
                        variant: 'success',
                    }
                );
            } catch (e) {
                this.errors = e.response.data.errors;
            }
            this.busy = false;
        },
        error(key) {
            if (this.errors.hasOwnProperty(key)) {
                return this.errors[key].join(', ');
            }
        },
        init() {
            this.user = {
                name: '',
                email: '',
                password: this.keygen(20),
                sendResetLink: false,
            };
        },
        makePassword() {
            this.user.password = this.keygen(20);
        },
        keygen(length) {
            var result = '';
            var characters =
                '!§$%&()?+-_.ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(
                    Math.floor(Math.random() * charactersLength)
                );
            }
            return result;
        },
    },
    computed: {
        score() {
            return zxcvbn(this.user.password).score;
        },
        usernameState() {
            if (this.errors.hasOwnProperty('name')) {
                return false;
            }
            return this.user.name.length > 0;
        },
        passwordState() {
            return this.score == 4;
        },
        variant() {
            switch (this.score) {
                case 0:
                case 1:
                    return 'danger';
                    break;
                case 2:
                case 3:
                    return 'warning';
                    break;
                case 4:
                    return 'success';
                    break;
            }
        },
        emailState() {
            let valid = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(
                this.user.email
            );

            if (!valid) {
                return false;
            }
            if (this.errors.hasOwnProperty('email')) {
                return false;
            }
            return true;
        },
        email() {
            return this.user.email;
        },
        name() {
            return this.user.name;
        },
    },
    watch: {
        email(val) {
            if (this.errors.hasOwnProperty('email')) {
                Vue.delete(this.errors, 'email');
            }
        },
        name(val) {
            if (this.errors.hasOwnProperty('name')) {
                Vue.delete(this.errors, 'name');
            }
        },
    },
};
</script>

<style></style>
