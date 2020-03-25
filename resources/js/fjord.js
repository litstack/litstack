require('./common/bootstrap');
require('./common/string');
require('./common/axios');
require('./common/window');

require('./service/component.service');
require('./service/page.service');
require('./service/library.service');

/**
 * The Fjord Application
 *
 *
 */
import FjordApp from './FjordApp';

/**
 * A simple event-bus
 *
 *
 */
import Bus from './common/event.bus';
Vue.prototype.$Bus = Bus;

import store from './store';
import mixins from './common/mixins';
import i18n from './common/i18n';

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
        el: '#fjord-app',
        i18n,
        store: store.store,
        components: {
            FjordApp
        }
    });
};

export default Fjord;
