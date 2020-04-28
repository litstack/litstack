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

    /**
     * Get attribute.
     *
     * @param {*} target
     * @param {*} prop
     */
    get(target, prop) {
        return this[prop] || this.attributes[prop] || undefined;
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
