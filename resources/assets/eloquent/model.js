import EloquentCollection from './collection';
import Bus from './../common/event.bus';
import store from './../store';

export default class EloquentModel {
    constructor(config) {
        if (this.isCollection(config)) {
            return new EloquentCollection(config, this.constructor);
        }

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

    get(target, prop) {
        return this[prop] || this.attributes[prop] || undefined;
    }

    isCollection(config) {
        return Array.isArray(config.data) || !config.data;
    }

    setAttributes(attributes) {
        this.attributes = attributes;

        this.setOriginalAttributes();
    }

    hasChanges() {
        // TODO:
    }

    setOriginalAttributes() {
        // is used to locate changes
        this.startingAttributes = JSON.parse(JSON.stringify(this.attributes));
    }

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

    getPayload() {
        return Object.assign({}, this.attributes);
    }

    async save() {
        let method = this.attributes.id ? 'put' : 'post';
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
    }

    async delete() {
        let route = `eloquent/destroy/${this.attributes.id}`;
        let response = await axios.post(route, { model: this.model });
        Bus.$emit('deletedModel', this);
    }
}
