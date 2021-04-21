<template>
    <lit-col :width="width" :class="field.class">
        <div
            :class="`${field.no_title ? '' : 'pb-3'} lit-form lit-form-item-${
                field.id
            }`"
        >
            <label
                class="lit-form-item-title mb-2 d-flex justify-content-between"
                :for="field.id"
                v-if="!field.no_title"
            >
                <span v-html="field.title"></span>
                <div>
                    <slot name="title-right" />
                    <b-badge v-if="field.translatable" variant="secondary">
                        <small>{{ language }}</small>
                    </b-badge>

                    <template v-if="field.info">
                        <lit-fa-icon
                            :id="`lit-form-item-${field.id}-info`"
                            icon="question-circle"
                            class="text-secondary"
                        />
                        <b-tooltip
                            :target="`lit-form-item-${field.id}-info`"
                            :delay="10"
                        >
                            {{ field.info }}
                        </b-tooltip>
                    </template>
                </div>
            </label>
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
                    v-html="field.hint && !noHint ? _format(field.hint, model) : ''"
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
    </lit-col>
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
            required: true,
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
            },
        },

        /**
         * Max.
         */
        max: {
            type: Number,
        },
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
            messages: [],
        };
    },
    watch: {
        state(val) {
            this.$emit('state', val);
        },
    },
    beforeMount() {
        this.$on('refresh', () => {
            this.$forceUpdate();
        });
        Lit.bus.$on('saved', this.checkForErrors);
        Lit.bus.$on('response', this.handleResponse);
        Lit.bus.$on('saveCanceled', this.resetErrors);
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

        handleResponse(response) {
            if(!response.config.url.endsWith('handle-event')) {
                return;
            }
            if(!this.field.for_action) {
                return;
            }
            this.checkForErrorsInResponse(response)
        },

        /**
         * Check results for erros.
         *
         * @param {Array} results
         * @return undefined
         */
        checkForErrors(results) {
            let response = results.findFailed(
                this.field._method,
                this.field.route_prefix
            );

            if (!response) {
                return this.resetErrors();
            }

            this.checkForErrorsInResponse(response);
        },

        /**
         * Check for erros in response.
         *
         * @param {Object} response
         * @return undefined
         */
        checkForErrorsInResponse(response) {
            if (!response.isAxiosError) {
                return this.resetErrors();
            }

            let errors = this.findErrors(response);
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
            if (typeof result.response.data != typeof {}) {
                return;
            }
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
        },
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
        },
    },
};
</script>
