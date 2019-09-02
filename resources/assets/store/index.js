import Vuex from 'vuex'

import config from './config.module'
import savings from './savings.module'

const modules = {
    config,
    savings
}

class FjordStore {
    constructor(modules) {
        this.store = null

        return new Proxy(this, this)
    }

    createStore(assign) {
        this.store = new Vuex.Store({
            modules: Object.assign(assign, modules)
        })
    }

    get(target, prop) {
        return this[prop] || this.store[prop]
    }
}

export const store = new FjordStore()

export default store
