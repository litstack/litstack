const methods = {
    init() {
        this._values = {};
        this.getValue();
        this.setOriginalValue();

        Fjord.bus.$on('languageChanged', this.getValue);
        Fjord.bus.$on('saved', this.onSaved);
        Fjord.bus.$on('saveCanceled', this.resetValues);
    },

    onSaved(results) {
        if (results.hasFailed(this.field._method, this.field.route_prefix)) {
            return;
        }

        this.setOriginalValue();
    },

    /**
     * Reset values when savings are canceled.
     */
    resetValues() {
        if (!this.hasValueChanged()) {
            return;
        }

        // For non translatable fields
        if (!this.field.translatable) {
            return this._setValue(this.original);
        }

        // For translatable fields.
        let locales = this.$store.state.config.languages;
        for (let i in locales) {
            let locale = locales[i];
            let original = this.original[locale];
            this._setValue(original, locale);
        }
        this.getValue();
    },

    /**
     * Set original values. This is used to locate changes.
     */
    setOriginalValue() {
        if (!this.field.translatable) {
            if (this.value) {
                this.original = Fjord.clone(this.value);
            }
            return;
        }

        this.original = {};
        let locales = this.$store.state.config.languages;
        for (let i in locales) {
            let locale = locales[i];
            let value = this._getValue(locale);
            this.original[locale] = null;
            if (!value) {
                continue;
            }
            this.original[locale] = value;
        }
    },

    /**
     * Get current locale.
     */
    getLocale() {
        return this.$store.state.config.language;
    },

    /**
     * Set value.
     */
    getValue() {
        this.value = this._getValue(this.getLocale());
        this.$forceUpdate();
    },

    /**
     * Get value from model by locale.
     *
     * @param {String} locale
     * @return {*}
     */
    _getValue(locale) {
        this.setDefaultValues(locale);

        if (this.field.translatable) {
            return this.model[locale][this.field.local_key];
        }
        if (this.model.usesJsonCast()) {
            this.model.attributes[this.field.local_key];
        }

        return this.model[this.field.local_key];
    },

    /**
     * Set new value to model, receive value from model after and add or remove
     * saveJob.
     *
     * @param {*} value
     */
    setValue(value) {
        this._setValue(value);
        this.getValue();
        this.addSaveJob(value);
    },

    /**
     * Set value to model.
     *
     * @param {*} value
     */
    _setValue(value, locale = null) {
        if (!locale) {
            locale = this.getLocale();
        }

        if (this.field.translatable) {
            return (this.model[locale][this.field.local_key] = value);
        }
        if (this.model.usesJsonCast()) {
            return (this.model.attributes[this.field.local_key] = value);
        }

        return (this.model[this.field.local_key] = value);
    },

    /**
     * Initialize default values for missing object keys.
     *
     * @param {String} locale
     */
    setDefaultValues(locale) {
        if (this.model.translatable && !(locale in this.model.attributes)) {
            this.model[locale] = {};
        }

        if (
            this.field.translatable &&
            !(this.field.local_key in this.model[locale])
        ) {
            this.model[locale][this.field.local_key] = null;
        }

        if (
            !this.field.translatable &&
            !(this.field.local_key in this.model.attributes)
        ) {
            this.model[this.field.local_key] = null;
        }
    },

    /**
     * Has value changed.
     *
     * @return {Boolean}
     */
    hasValueChanged() {
        if (!this.field.translatable) {
            return this.original != this.value;
        }

        return this.original[this.getLocale()] != this.value;
    },

    /**
     * Add save job to store.
     *
     * @param {*} value
     */
    addSaveJob(value) {
        let locale = this.getLocale();
        let params = {};
        let jobKey = '';

        if (this.field.translatable) {
            jobKey = `${locale}.${this.field.local_key}`;
            params[locale] = {
                [this.field.local_key]: value
            };
        } else {
            jobKey = `${this.field.local_key}`;
            params[this.field.local_key] = value;
        }

        let job = {
            route: this.field.route_prefix,
            method: this.field._method,
            params,
            key: jobKey
        };

        if (!this.hasValueChanged()) {
            this.$store.commit('REMOVE_SAVE_JOB', job);
        } else {
            this.$store.commit('ADD_SAVE_JOB', job);
        }
    }
};

export default methods;
