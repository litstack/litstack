require('./common/bootstrap');
require('./common/string');
require('./common/axios');
require('./common/window');

require('./service/component.service');
require('./service/library.service');

/**
 * Load all required components for the FjordApp
 */
import FjordApp from './FjordApp';
import Navigation from './components/Partials/Navigation';
import Notify from './components/Notify';
import Bus from './common/event.bus';
Vue.prototype.$Bus = Bus;

import store from './store';
import mixins from './common/mixins';
import i18n from './common/i18n';

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

Bus.$on('setLocale', locale => {
    i18n.locale = locale;
});

export default Fjord;
