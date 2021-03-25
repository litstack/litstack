<script>
import { mapState, mapGetters } from 'vuex';

import dependencyMethods from './dependecy_methods';

export default {
    name: 'Field',

    /**
     * Rendering the lit-field component.
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
                ...props,
            },
            on: {
                input: this.input,
                // TODO: Except input ?
                //...this.$listeners
            },
            ref: 'field',
        });

        return vm;
    },
    props: {
        /**
         * Model.
         */
        model: {
            type: Object,
            required: true,
        },

        /**
         * Model id.
         */
        modelId: {
            type: [Number, String],
            default() {
                return null;
            },
        },

        /**
         * Field attributes.
         */
        field: {
            type: Object,
            required: true,
        },

        /**
         * Determine's if field changes should be added to save jobs.
         */
        save: {
            type: Boolean,
            default: true,
        },
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
            method: 'PUT',

            /**
             * Save job id.
             */
            jobId: null,

            /**
             * cleave mask.
             */
            cleave: null,

            /**
             * Current mask object.
             */
            mask: null,
        };
    },
    beforeMount() {
        // Route prefix stuff.
        this.formatRoutePrefix();

        // Field value stuff.
        this.storeOriginalValues();
        this.setCurrentValue();
        Lit.bus.$on('saveCanceled', this.resetModelValuesToOriginal);
        Lit.bus.$on('languageChanged', this.setCurrentValue);
        Lit.bus.$on('saved', this.onSaved);
        this.$on('input', this.input);

        // Render dependency stuff.
        this.resolveDependecies(this.field.dependencies);
        Lit.bus.$on('resolveDependencies', () => {
            this.resolveDependecies(this.field.dependencies);
        });
        Lit.bus.$on('fieldChanged', () => {
            Lit.bus.$emit('resolveDependencies');
            this.applyMask();
        });

        this.$on('setSaveJobId', id => {
            this.jobId = id;
        });
    },
    mounted() {
        this.applyMask();
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
        },
    },
    methods: {
        ...dependencyMethods,

        /**
         * v-on:input
         *
         * @return {undefined}
         */
        input(newValue) {
            if (this.field.readonly) {
                return;
            }

            this.value = newValue;

            this.fillValueToModel(newValue);

            if (this.save) {
                this.addSaveJob();
            }

            this.$emit('changed', newValue);
            Lit.bus.$emit('fieldChanged', this.field.local_key);
        },

        /**
         * Apply mask to field.
         */
        applyMask() {
            if (!this.field.mask) {
                return;
            }

            let input = this.$refs.field.$refs.input;

            if (!input) {
                return;
            }

            let mask = this.getMask();

            // Destory old cleave object if mask has changed.
            if (!_.isEqual(this.mask, mask) && this.cleave) {
                console.log('Changed');
                this.cleave.destroy();
            }

            this.mask = mask;

            this.cleave = new Cleave(input.$el, Lit.clone(mask));
        },

        /**
         * Compute mask.
         */
        getMask() {
            let mask = Lit.clone(this.field.mask);

            for (let key in mask) {
                if (typeof mask[key] === 'string') {
                    mask[key] = this._format(mask[key], this.model);
                }
            }

            return mask;
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

        getKey() {
            this.field.local_key;
        },

        getKeys() {
            return this.field.local_keys;
        },

        fieldModifiesMultipleValues() {
            return !this.field.local_key 
                && this.field.local_keys instanceof Array;
        },

        /**
         * Fill value to model.
         *
         * @param {*} value
         * @param {String} locale
         * @return {undefined}
         */
        fillValueToModel(value, locale = null) {
            if(!this.fieldModifiesMultipleValues()) {
                return this.fillAttributeValueToModel(this.field.local_key, value, locale);
            }

            for(let i=0;i<this.field.local_keys.length;i++) {
                let attribute = this.field.local_keys[i];

                this.fillAttributeValueToModel(attribute, value[attribute], locale);
            }
        },

        fillAttributeValueToModel(attribute, value, locale) {
            if (!locale) {
                locale = this.language;
            }

            // Translatable field.
            if (this.field.translatable) {
                if (!(locale in this.model)) {
                    this.model[locale] = {};
                }

                return (this.model[locale][attribute] = value);
            }

            // Lit model.
            if (this.model.usesJsonCast()) {
                return (this.model.attributes[attribute] = value);
            }

            // Default non translatable field.
            return (this.model[attribute] = value);
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
         * @return {any}
         */
        getValueFromLanguage(locale) {
            this.setDefaultModelValuesForLocale(locale);

            if(!this.fieldModifiesMultipleValues()) {
                return this.getAttributeValueFromLanguage(
                    this.field.local_key, locale
                );
            }

            let values = {}

            for(let i=0;i<this.field.local_keys.length;i++) {
                let attribute = this.field.local_keys[i];

                values[attribute] = this.getAttributeValueFromLanguage(
                    attribute, locale
                );
            }

            return values;
        },

        /**
         * Get attribute value from model by locale.
         *
         * @param {String} locale
         * @return {any}
         */
        getAttributeValueFromLanguage(attribute, locale) {            
            if (this.field.translatable) {
                return this.model[locale][attribute];
            }
            if (this.model.usesJsonCast()) {
                return this.model.attributes[attribute];
            }

            if (this.field.is_pivot) {
                attribute = `pivot.${attribute}`;
            }

            return this.model[attribute];
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
                this.original = Lit.clone(
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
                this.original[locale] = Lit.clone(value);
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
                    ...(this.field.params || {}),
                },
                key: this.getSaveJobKey(),
                route: this.routePrefix,
                method: this.method,
            };

            if (this.jobId) {
                job.id = this.jobId;
            }

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
            if (Array.isArray(value) || typeof value === 'object') {
                return !_.isEqual(original, value);
            }

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
                ? { [this.language]: { [this.field.id]: value } }
                : { [this.field.id]: value };
        },

        /**
         * Get save job key.
         *
         * @return {String}
         */
        getSaveJobKey() {
            return this.field.translatable
                ? `${this.language}.${this.field.id}`
                : this.field.id;
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

            if (modelId === undefined || modelId === null) {
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
        },
    },
};
</script>
