import store from '@lit-js/store';

/**
 * Crud Model.
 */
export default class CrudModel {
    /**
     * Create new CrudModel instance.
     *
     * @param {*} config
     */
    constructor(config) {
        this.originals = {};
        this.attributes = config.attributes;
        this.translatable = config.translatable;
        this.cast = config.cast;

        this.setOriginals();

        return new Proxy(this, this);
    }

    _getTranslatedAttribute(key, attributes) {
        let lng = store.state.config.language;

        if (key in attributes) {
            return attributes[key];
        }

        if (!(lng in attributes)) {
            return attributes[key] || undefined;
        }

        if (key in attributes[lng]) {
            return attributes[lng][key];
        }

        return attributes[key];
    }

    /**
     * Set attribute
     *
     * @param {*} obj
     * @param {*} prop
     * @param {*} value
     */
    set(obj, prop, value) {
        obj = this.attributes;

        return Reflect.set(obj, prop, value);
    }

    /**
     * Get attribute.
     *
     * @param {*} target
     * @param {*} prop
     */
    get(target, prop) {
        if (String(prop).startsWith('Symbol(Symbol.')) {
            return Reflect.get(...arguments);
        }

        let attribute =
            this[prop] ||
            this.attributes[prop] ||
            this._getTranslatedAttribute(prop, this.attributes);

        if (attribute !== undefined) {
            return attribute;
        }

        // Get props for relation keys like department.name
        prop = String(prop);
        if (!prop.includes('.')) {
            return;
        }

        attribute = JSON.parse(JSON.stringify(this.attributes));

        let keys = String(prop).split('.');
        for (let i = 0; i < keys.length; i++) {
            let key = keys[i];
            if (!attribute || typeof attribute != typeof {}) {
                return;
            }
            attribute = this._getTranslatedAttribute(key, attribute);
        }

        return attribute;
    }

    /**
     * Is the Crud Model casting the field values into one json column.
     *
     * @return {Boolean}
     */
    usesJsonCast() {
        return this.cast;
    }

    /**
     * Set clone of original attributes. This is used to located changes.
     *
     * @param {*} attributes
     * @return {undefined}
     */
    setOriginals() {
        // is used to locate changes
        this.originals = JSON.parse(JSON.stringify(this.attributes));
    }

    /**
     * Check if attributes have changed.
     *
     * @return {Boolean}
     */
    hasChanges() {
        return (
            JSON.stringify(this.attributes) !== JSON.stringify(this.originals)
        );
    }
}
