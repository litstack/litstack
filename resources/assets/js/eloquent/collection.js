
import Eloquent from './index'
import Bus from './../common/event.bus'

export default class EloquentCollection {
    constructor(config) {

        this.items = collect(config.data).map(item => {
            let c = config
            c.data = item
            return new Eloquent(c)
        })

        this.config = config
        this.translatable = config.translatable
        this.model = config.model
        this.route = config.route

        let self = this
        Bus.$on('deletedModel', (model) => {self._checkItems(model)})

        return new Proxy(this, this)
    }

    _checkItems(model) {
        this.items = this.items.filter(item => model != item)
        console.log(this, this.items)
    }


    get(target, prop) {
        return this[prop] || this.items[prop] || undefined;
    }

    _setData(items) {
        for(let i=0;i<items.length;i++) {
            if(i >= this.items.items.length) {
                continue;
            }

            try {
                this.items.items[i].setData(items[i])
            } catch(e) {
                console.log(e, 'OH NO', this.items.items[i])
            }
        }
    }

    async save() {
        let route = `/admin/eloquent/save-all`
        let payload = {
            items: this.items.map(item => item.getPayload()).toArray()
        }
        let response = await axios['post'](route, payload)

        this._setData(response.data)
    }

    delete() {

    }
}
