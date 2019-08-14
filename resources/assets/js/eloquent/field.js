
import store from './../store'

import Bus from './../common/event.bus'

export default class Field {

    constructor(config, item, route) {
        this.config = config
        this.item = item
        this.originalItem = JSON.parse(JSON.stringify(item))
        this.route = route
        this.translatable = false

        let self = this
        Bus.$on('modelsSaved', () => {
            self.originalItem = JSON.parse(JSON.stringify(self.item))
        })

        return new Proxy(this, this)
    }

    _newEmptyField() {

    }

    hasChanged() {
        return this.originalItem != this.model
    }

    get(target, prop) {
        return this[prop] || this.config[prop] || undefined
    }

    get originalModel() {
        if(this.translatable) {
            if(!(this.config.id in this.originalItem[store.state.config.language])) {
                this.originalItem[store.state.config.language][this.config.id] = null;
            }
        }
        return this.translatable
            ? this.originalItem[store.state.config.language][this.config.id]
            : this.originalItem[this.config.id]
    }

    get model() {
        if(this.translatable) {
            if(!(this.config.id in this.item[store.state.config.language])) {
                this.item[store.state.config.language][this.config.id] = null;
            }
        }
        return this.translatable
            ? this.item[store.state.config.language][this.config.id]
            : this.item[this.config.id]
    }

    set model(val) {
        if(this.translatable) {
            this.item[store.state.config.language][this.config.id] = val
        } else {
            this.item[this.config.id] = val
        }
    }
}
