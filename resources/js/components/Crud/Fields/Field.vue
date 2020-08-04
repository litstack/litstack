<script>
import { mapState, mapGetters } from 'vuex';

import dependencyMethods from './dependecy_methods';

export default {
    name: 'Field',

    /**
     * Rendering the fj-field component.
     *
     * @param {Function} createElement
     * @return {Object}
     */
    render(createElement) {
        if (!this.shouldRender) {
            return;
        }

        let props = this.field.props ? this.field.props : {};
        let modelId = this.modelId === 0 ? this.model.id : this.modelId;

        let vm = createElement(this.field.component, {
            props: {
                field: this.field,
                model: this.model,
                value: this.value,
                modelId,
                ...props
            },
            on: {
                input: this.input
                // TODO: Except input ?
                //...this.$listeners
            }
        });

        return vm;
    },
    props: {
        /**
         * Model.
         */
        model: {
            type: Object,
            required: true
        },

        /**
         * Model id.
         */
        modelId: {
            type: [Number, String],
            default() {
                return null;
            }
        },

        /**
         * Field attributes.
         */
        field: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            /**
             * Determines if the field fulfills conditions.
             */
            fulfillsConditions: true,

            /**
             * Field value.
             */
            value: null,

            /**
             * Field value originals. This is used to detect changes.
             */
            original: null,

            /**
             * Fields route prefix.
             */
            routePrefix: '',

            /**
             * Request method.
             */
            method: 'PUT'
        };
    },
    beforeMount() {
        // Route prefix stuff.
        this.formatRoutePrefix();

        // Field value stuff.
        this.storeOriginalValues();
        this.setCurrentValue();
        Fjord.bus.$on('saveCanceled', this.resetModelValuesToOriginal);
        Fjord.bus.$on('languageChanged', this.setCurrentValue);
        Fjord.bus.$on('saved', this.onSaved);

        // Render dependency stuff.
        this.resolveDependecies(this.field.dependencies);
        Fjord.bus.$on('fieldChanged', () =>
            this.resolveDependecies(this.field.dependencies)
        );
    },
    computed: {
        ...mapGetters(['language']),

        /**
         * Determines if the component should be rendered.
         *
         * @return {Boolean}
         */
        shouldRender() {
            if (!this.field.dependencies) {
                return true;
            }

            return this.fulfillsConditions;
        }
    },
    methods: {
        ...dependencyMethods,

        /**
         * v-on:input
         *
         * @return {undefined}
         */
        input(newValue) {
            this.value = newValue;
            this.fillValueToModel(newValue);
            this.addSaveJob();
            this.$emit('changed');
            Fjord.bus.$emit('fieldChanged', this.field.local_key);
        },

        /**
         * On saved.
         *
         * @return {undefined}
         */
        onSaved(results) {
            if (results.hasFailed(this.method, this.routePrefix)) {
                return;
            }

            this.storeOriginalValues();
        },

        /**
         * Fill value to model.
         *
         * @param {*} value
         * @param {String} locale
         * @return {undefined}
         */
        fillValueToModel(value, locale = null) {
            if (!locale) {
                locale = this.language;
            }

            // Translatable field.
            if (this.field.translatable) {
                return (this.model[locale][this.field.local_key] = value);
            }

            // Fjord model.
            if (this.model.usesJsonCast()) {
                return (this.model.attributes[this.field.local_key] = value);
            }

            // Default non translatable field.
            return (this.model[this.field.local_key] = value);
        },

        /**
         * Reset model values to original values.
         *
         * @return {undefined}
         */
        resetModelValuesToOriginal() {
            if (!this.field.translatable) {
                // For non translatable fields.
                this.model.attributes[this.field.local_key] = this.original;
            } else {
                // For translatable fields.
                if (!(typeof this.original == 'object')) {
                    return;
                }

                for (let locale in this.original) {
                    this.model.attributes[locale][
                        this.field.local_key
                    ] = this.original[locale];
                }
            }

            this.setCurrentValue();
        },

        /**
         * Set current field value.
         *
         * @return {undefined}
         */
        setCurrentValue() {
            this.value = this.getValueFromLanguage(this.language);
        },

        /**
         * Get value from model by locale.
         *
         * @param {String} locale
         * @return {undefined}
         */
        getValueFromLanguage(locale) {
            this.setDefaultModelValuesForLocale(locale);

            if (this.field.translatable) {
                return this.model[locale][this.field.local_key];
            }
            if (this.model.usesJsonCast()) {
                return this.model.attributes[this.field.local_key];
            }

            return this.model[this.field.local_key];
        },

        /**
         * Initialize default model values for missing object keys.
         *
         * @param {String} locale
         * @return {undefined}
         */
        setDefaultModelValuesForLocale(locale) {
            // Translatable model and missing locale in attributes.
            if (
                (this.model.translatable || this.field.translatable) &&
                !(locale in this.model.attributes)
            ) {
                this.model[locale] = {};
            }

            // Translatable field and missing attribute in model[locale]
            if (
                this.field.translatable &&
                !(this.field.local_key in this.model[locale])
            ) {
                this.model[locale][this.field.local_key] = null;
            }

            // Non translatable field and missing attribute in model attributes.
            if (
                !this.field.translatable &&
                !(this.field.local_key in this.model.attributes)
            ) {
                this.model[this.field.local_key] = null;
            }
        },

        /**
         * Store original values.
         *
         * @return {undefined}
         */
        storeOriginalValues() {
            if (!this.field.translatable) {
                this.original = Fjord.clone(
                    this.getValueFromLanguage(this.language)
                );

                return;
            }

            // Set originals for all locales.
            this.original = {};
            let locales = this.$store.state.config.languages;
            for (let i in locales) {
                let locale = locales[i];
                let value = this.getValueFromLanguage(locale);
                this.original[locale] = null;
                if (!value) {
                    continue;
                }
                this.original[locale] = value;
            }
        },

        /**
         * Add save job to store.
         *
         * @return {undefined}
         */
        addSaveJob() {
            if (this.field.storable === false) {
                return;
            }

            let job = {
                params: {
                    payload: this.getSaveJobPayload(this.value),
                    ...(this.field.params || {})
                },
                key: this.getSaveJobKey(),
                route: this.routePrefix,
                method: this.method
            };

            //console.log('CHANGED', this.field.route_prefix, this.value);

            if (this.hasValueChanged()) {
                this.$store.commit('ADD_SAVE_JOB', job);
            } else {
                this.$store.commit('REMOVE_SAVE_JOB', job);
            }
        },

        /**
         * Has value changed.
         *
         * @return {Boolean}
         */
        hasValueChanged() {
            if (!this.field.translatable) {
                return this.compareValues(this.original, this.value);
            }

            return this.compareValues(this.original[this.language], this.value);
        },

        /**
         * Compares values.
         *
         * @return {Boolean}
         */
        compareValues(original, value) {
            if (original !== null) {
                return original != this.value;
            }

            if (Array.isArray(value)) {
                return value.length > 0;
            }

            return !!value;
        },

        /**
         * Get params for current save job.
         *
         * @return {Object}
         */
        getSaveJobPayload(value) {
            return this.field.translatable
                ? { [this.language]: { [this.field.local_key]: value } }
                : { [this.field.local_key]: value };
        },

        /**
         * Get save job key.
         *
         * @return {String}
         */
        getSaveJobKey() {
            return this.field.translatable
                ? `${this.language}.${this.field.local_key}`
                : this.field.local_key;
        },

        /**
         * Format field route_prefix.
         *
         * @return {undefined}
         */
        formatRoutePrefix() {
            // This allows Fields like Blocks to set individual Model id's that
            // differ from the id of the model that gets passed to the Field.
            // For e.g: In a Block the model of the Block would be passed but the
            // route Model id is not the id for the Block but for the Crud Model.
            let modelId = this.modelId || this.model.id;
            let replace = '{id}';
            this.method = 'PUT';

            if (modelId === undefined) {
                this.method = 'POST';
                modelId = '';
                replace = '/{id}';
            }

            this.routePrefix = this.field.route_prefix.replace(
                replace,
                modelId
            );

            this.field._method = this.method;
            this.field.route_prefix = this.routePrefix;
        }
    }
};
</script>
