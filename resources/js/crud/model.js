import store from '@fj-js/store';

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

        if (!this.translatable) {
            return;
        }

        if (!('translation' in attributes)) {
            return attributes[key] || null;
        }

        if (!(lng in attributes)) {
            return attributes[key] || null;
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

    setAttribute(key, value) {
        console.log('SET', key, value);
        this.attributes[key] = value;
    }

    /**
     * Get attribute.
     *
     * @param {*} target
     * @param {*} prop
     */
    get(target, prop) {
        let attribute =
            this[prop] ||
            this.attributes[prop] ||
            this._getTranslatedAttribute(prop, this.attributes);

        if (attribute) {
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

            if (!attribute) {
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
