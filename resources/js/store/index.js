import Vuex from 'vuex';

import config from './modules/config.module';
import savings from './modules/savings.module';
import form from './modules/form.module';
import auth from './modules/auth.module';

const modules = {
    config,
    savings,
    form,
    auth
};

class FjordStore {
    constructor(modules) {
        this.store = null;

        return new Proxy(this, this);
    }

    createStore(assign) {
        this.store = new Vuex.Store({
            modules: Object.assign(assign, modules)
        });
    }

    get(target, prop) {
        return this[prop] || this.store[prop];
    }
}

export const store = new FjordStore();

export default store;
