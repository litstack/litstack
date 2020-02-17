require('./bootstrap');

import Vuex from 'vuex';
import FjordApp from './FjordApp';
import Navigation from './components/Partials/Navigation';
import Notify from './components/Notify';

import store from './store';
import mixins from './bootstrap/mixins';
import i18n from './service/i18n';

const APP_ID = '#fjord-app';

function Fjord(options) {
    this.store = null;
    this._mixins = mixins;

    this._init(options);
}

Fjord.prototype._init = function(options) {
    if ('store' in options) {
        this._store_modules = Object.assign(options.store, this._store_modules);
    }

    if ('mixins' in options) {
        this._mixins = Object.assign(options.mixins, this._mixins);
    }

    Vue.mixin({
        methods: this._mixins
    });

    store.createStore(this._store_modules);
    this._vue();
};

Fjord.prototype._vue = function() {
    this.app = new Vue({
        el: APP_ID,
        i18n,
        store: store.store,
        components: {
            FjordApp,
            Navigation,
            Notify
        }
    });
};

export default Fjord;
