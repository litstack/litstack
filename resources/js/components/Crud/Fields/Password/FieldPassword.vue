<template>
    <lit-base-field
        :field="field"
        :model="model"
        ref="form"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <b-input-group>
            <b-input
                class="form-control"
                :value="value"
                :placeholder="field.placeholder"
                :type="show ? 'text' : 'password'"
                :state="state"
                v-bind:readonly="field.readonly"
                aria-describedby="input-live-feedback"
                @input="changed"
            />
            <b-input-group-append>
                <b-button
                    :variant="show ? 'primary' : 'outline-secondary'"
                    @click="show = !show"
                >
                    <lit-fa-icon icon="eye" />
                </b-button>
            </b-input-group-append>
        </b-input-group>
        <!-- <div
            class="lit-field-password-score"
            v-if="!_.isEmpty(password) && !field.noScore"
        >
            <b-progress
                class="mt-2"
                height="0.5rem"
                :value="score"
                :max="4"
                :variant="scoreVariant"
            />
        </div> -->
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldPassword',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            value: '',
            show: false,
            password: null,
        };
    },
    beforeMount() {
        if (!this.field.noScore) {
            this.field.hint = ``;
        }

        Lit.bus.$on('saveCanceled', this.reset);
    },
    methods: {
        setState(state) {
            this.state = state;
            if (state === false) {
                this.$emit('error');
            }
        },
        reset() {
            this.$emit('input', '');
        },
        changed(newPassword) {
            this.password = newPassword;
            this.addSaveJob(newPassword);
            if (newPassword == '') {
                return (this.field.hint = ``);
            }
            if (this.field.noScore) {
                return;
            }
            //this.field.hint = `Password strength: <b>${this.scoreStrength}</b>`;
            this.$refs.form.$forceUpdate();
        },
        addSaveJob(newPassword) {
            let job = {
                route: this.field.route_prefix,
                method: this.field._method,
                params: {
                    payload: { [this.field.local_key]: newPassword },
                    ...(this.field.params || {}),
                },
                key: this.field.local_key,
            };

            if (newPassword == '') {
                this.$store.commit('REMOVE_SAVE_JOB', job);
            } else {
                this.$store.commit('ADD_SAVE_JOB', job);
            }
        },
    },
    computed: {
        score() {
            if (!this.password) {
                return 0;
            }
            // TODO: make password checker.
            return 0;
        },
        scoreVariant() {
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
        scoreStrength() {
            switch (this.score) {
                case 0:
                case 1:
                    return 'weak';
                    break;
                case 2:
                case 3:
                    return 'good';
                    break;
                case 4:
                    return 'strong';
                    break;
            }
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-field-password-score {
    width: 100%;
}
</style>
