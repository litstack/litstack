<template>
    <fj-form-item
        :field="field"
        :model="model"
        ref="form"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <b-input-group>
            <b-input
                class="form-control"
                v-model="value"
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
                    <fa-icon icon="eye" />
                </b-button>
            </b-input-group-append>
        </b-input-group>
        <div
            class="fj-field-password-score"
            v-if="!_.isEmpty(value) && !field.noScore"
        >
            <b-progress
                class="mt-2"
                height="0.5rem"
                :value="score"
                :max="4"
                :variant="scoreVariant"
            />
        </div>
    </fj-form-item>
</template>

<script>
export default {
    name: 'FieldPassword',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            value: '',
            show: false
        };
    },
    beforeMount() {
        if (!this.field.noScore) {
            this.field.hint = ``;
        }

        Fjord.bus.$on('saveCanceled', this.reset);
    },
    methods: {
        setState(state) {
            this.state = state;
            if (state === false) {
                this.$emit('error');
            }
        },
        reset() {
            this.value = '';
            this.changed('');
        },
        changed(val) {
            this.addSaveJob();
            if (val == '') {
                return (this.field.hint = ``);
            }
            if (this.field.noScore) {
                return;
            }
            this.field.hint = `Password strength: <b>${this.scoreStrength}</b>`;
            this.$refs.form.$forceUpdate();
        },
        addSaveJob() {
            let job = {
                route: this.field.route_prefix,
                method: this.field._method,
                params: {
                    [this.field.local_key]: this.value
                },
                key: this.field.local_key
            };

            let add = true;
            if (!this.field.noScore && this.score < this.field.minScore) {
                add = false;
            } else if (this.field.noScore && this.value == '') {
                add = false;
            }

            if (!add) {
                this.$store.commit('REMOVE_SAVE_JOB', job);
            } else {
                this.$store.commit('ADD_SAVE_JOB', job);
            }
        }
    },
    computed: {
        score() {
            return zxcvbn(this.value).score;
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
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
.fj-field-password-score {
    width: 100%;
}
</style>
