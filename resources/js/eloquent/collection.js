import FjordModel from './fjord.model';
import Bus from '@fj-js/common/event.bus';

export default class EloquentCollection {
    constructor(config, constructor) {
        this.items = collect(config.data).map(item => {
            let c = config;
            c.data = item;
            return new constructor(c);
        });

        this.config = config;
        this.translatable = config.translatable;
        this.model = config.model;
        this.route = config.route;

        let self = this;
        Bus.$on('deletedModel', model => {
            self._checkItems(model);
        });

        return new Proxy(this, this);
    }

    _checkItems(model) {
        this.items = this.items.filter(item => model != item);
        console.log(this, this.items);
    }

    get(target, prop) {
        return this[prop] || this.items[prop] || undefined;
    }

    _setAttributes(items) {
        for (let i = 0; i < items.length; i++) {
            if (i >= this.items.items.length) {
                continue;
            }

            this.items.items[i].setAttributes(items[i]);
        }
    }

    async save() {
        let promises = [];

        this.items.map(item => {
            promises.push(item.save());
        });

        let results = await Promise.all(promises);

        return results;
        /*
        let route = `eloquent/save-all`
        let payload = {
            items: this.items.map(item => item.getPayload()).toArray()
        }
        let response = await axios['post'](route, payload)

        this._setAttributes(response.data)
        */
    }

    delete() {}
}
