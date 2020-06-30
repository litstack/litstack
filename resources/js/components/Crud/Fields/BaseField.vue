<template>
    <fj-col :width="width" :class="field.class">
        <div :class="`pb-4 fjord-form fj-form-item-${field.id}`">
            <h6 class="fj-form-item-title mb-0 d-flex justify-content-between">
                <template v-if="field.slots">
                    <fj-slot
                        v-if="'title' in field.slots"
                        :name="field.slots.title"
                        :props="{ field, model }"
                    />
                </template>

                <label :for="field.id" v-else v-html="field.title"></label>
                <div>
                    <slot name="title-right" />
                    <b-badge v-if="field.translatable" variant="secondary">
                        <small>{{ language }}</small>
                    </b-badge>
                </div>
            </h6>
            <p class="text-secondary" v-if="field.info">{{ field.info }}</p>
            <div class="input-group">
                <slot :state="state" />
                <b-form-invalid-feedback
                    v-for="(message, key) in messages"
                    :key="key"
                    :style="`display:${state == null ? 'none' : 'block'}`"
                >
                    {{ message }}
                </b-form-invalid-feedback>
            </div>
            <div
                class="d-flex justify-content-between"
                v-if="
                    (field.hint && !noHint) ||
                        field.max ||
                        field.min ||
                        field.maxFiles
                "
            >
                <small
                    class="form-text text-muted"
                    v-html="field.hint && !noHint ? field.hint : ''"
                />

                <small class="form-text text-muted" v-if="value">
                    <template v-if="value && max">
                        {{ number }}/{{ max }}
                    </template>
                    <template v-else-if="value">
                        {{ number }}
                    </template>
                </small>
            </div>
        </div>
    </fj-col>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'BaseField',
    props: {
        /**
         * Field attributes.
         */
        field: {
            type: [Object, Array],
            required: true
        },

        /**
         * Model.
         */
        model: {},

        /**
         * Value.
         */
        value: {},

        /**
         * No hint.
         */
        noHint: {
            type: Boolean,
            default() {
                return false;
            }
        },

        /**
         * Max.
         */
        max: {
            type: Number
        }
    },
    data() {
        return {
            /**
             * Error stat.
             */
            state: null,

            /**
             * Error messages.
             */
            messages: []
        };
    },
    watch: {
        state(val) {
            this.$emit('state', val);
        }
    },
    beforeMount() {
        this.$on('refresh', () => {
            this.$forceUpdate();
        });
        Fjord.bus.$on('saved', this.checkForErrors);
        Fjord.bus.$on('saveCanceled', this.resetErrors);
    },
    methods: {
        /**
         * Reset errors.
         *
         * @return {undefined}
         */
        resetErrors() {
            this.state = null;
            this.messages = [];
        },

        /**
         * Check results for erros.
         *
         * @param {Array} results
         * @return
         */
        checkForErrors(results) {
            let result = results.findFailed(
                this.field._method,
                this.field.route_prefix
            );

            if (!result) {
                return this.resetErrors();
            }

            if (!result.isAxiosError) {
                return this.resetErrors();
            }

            let errors = this.findErrors(result);
            if (!errors || _.isEmpty(errors)) {
                return this.resetErrors();
            }

            this.state = false;
            this.messages = errors;
            this.$emit('error', errors);
        },

        /**
         * Find errors.
         *
         * @param {Object} result
         * @return {Array}
         */
        findErrors(result) {
            if (!('errors' in result.response.data)) {
                return;
            }
            if (!this.field.translatable) {
                return result.response.data.errors[this.field.local_key];
            }
            let errors = [];
            for (let key in result.response.data.errors) {
                let error = result.response.data.errors[key];
                if (key.endsWith('.' + this.field.local_key)) {
                    for (let i in error) {
                        let message = error[i];
                        errors.push(message);
                    }
                }
            }
            return errors;
        }
    },
    computed: {
        ...mapGetters(['language']),

        /**
         * Number from value.
         */
        number() {
            if (!this.value) {
                return 0;
            }
            if (typeof this.value == 'number') {
                return this.value;
            }

            return this.value.length;
        },

        /**
         * Col width.
         *
         * @return {Number}
         */
        width() {
            return this.field.width !== undefined ? this.field.width : 12;
        }
    }
};
</script>
