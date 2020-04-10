import EloquentCollection from './collection';
import Bus from '@fj-js/common/event.bus';
import store from '@fj-js/store';

export default class EloquentModel {
    constructor(config) {
        if (this.isCollection(config)) {
            return new EloquentCollection(config, this.constructor);
        }

        this.originals = {};
        this.attributes = {};
        this.config = config;

        this.model = config.model;
        this.route = config.route;
        this.relations = config.relations;

        this.deleted = false;
        this.saveable = false;
        this.setAttributes(config.data);
        this.saveable = true;

        this._setRelations();

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

    isCollection(config) {
        console.log('data:', config.data);
        return Array.isArray(config.data) || !config.data;
    }

    setAttributes(attributes) {
        for (let key in attributes) {
            this.attributes[key] = attributes[key];
        }

        this.setOriginals();
    }

    /**
     * Check if attributes have changed.
     *
     * @return {Boolean}
     */
    hasChanges() {
        // TODO:
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
     * Create EloquentModel instances for relations.
     *
     * @return {undefined}
     */
    _setRelations() {
        if (!this.attributes.eloquentJs) {
            return;
        }

        for (let relationName in this.attributes.eloquentJs) {
            this.attributes[relationName] = new this.constructor(
                this.attributes.eloquentJs[relationName]
            );
            this.relations[relationName] = this.attributes[relationName];
        }
    }

    _getParent(cls) {}

    /**
     * Get payload for save request.
     *
     * @return {Object}
     */
    getPayload() {
        return Object.assign({}, this.attributes);
    }

    /**
     * Send resource request store or update, depending on if the id isset.
     *
     * @return {undefined}
     */
    async save() {
        let method = this.attributes.id ? 'put' : 'post';

        if (this.route == 'form_fields') {
            this.route = store.state.form.config.route;
        }

        let route = `${this.route}/${
            this.attributes.id ? this.attributes.id : ''
        }`;

        // strip trailing slashes
        let response = await axios[method](
            route.replace(/\/$/, ''),
            this.getPayload()
        );

        this.setAttributes(response.data);
        this._setRelations();

        return response;
    }

    /**
     * Send resource request destroy.
     *
     * @return {Object} response
     */
    async delete() {
        let route = `eloquent/destroy/${this.attributes.id}`;
        let response = await axios.post(route, { model: this.model });
        Bus.$emit('deletedModel', this);
    }
}
