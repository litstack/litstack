const methods = {
    init() {
        this.getValue();
        this.setOriginalValue();

        Fjord.bus.$on('languageChanged', this.getValue);
        Fjord.bus.$on('saved', () => {
            this.setOriginalValue();
        });
    },
    setOriginalValue() {
        if (this.value) {
            this.original = Fjord.clone(this.value);
        }
    },
    getLocale() {
        return this.$store.state.config.language;
    },
    getValue() {
        this.value = this._getValue();
        this.$forceUpdate();
    },
    _getValue() {
        let locale = this.getLocale();

        this.setDefaultValues();

        if (this.field.translatable) {
            return this.model[locale][this.field.local_key];
        }
        if (this.model.usesJsonCast()) {
            this.model.attributes[this.field.local_key];
        }

        return this.model[this.field.local_key];
    },
    setValue(value) {
        this._setValue(value);
        this.getValue();
        this.addSaveJob(value);
    },
    _setValue(value) {
        let locale = this.getLocale();

        if (this.field.translatable) {
            return (this.model[locale][this.field.local_key] = value);
        }
        if (this.model.usesJsonCast()) {
            return (this.model.attributes[this.field.local_key] = value);
        }

        return (this.model[this.field.local_key] = value);
    },
    setDefaultValues() {
        let locale = this.getLocale();

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

        if (this.original == this.value) {
            this.$store.commit('REMOVE_SAVE_JOB', job);
        } else {
            this.$store.commit('ADD_SAVE_JOB', job);
        }

        // TODO: Remove save job if has no changes:

        /*
        if (this.model.hasChanges()) {
            this.$store.commit('ADD_SAVE_JOB', job);
        } else {
            job.params = removeParams;
            this.$store.commit('REMOVE_SAVE_JOB', job);
        }
        */
    }
};

export default methods;
