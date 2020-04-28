require('./common/bootstrap');
require('./common/string');
require('./common/array');
require('./common/axios');
require('./common/window');
require('./common/fjord');

require('./service/component.service');
require('./service/page.service');
require('./service/library.service');

/**
 * The Fjord Application
 */
import FjordApp from './FjordApp';

/**
 * A simple event-bus
 */
import Bus from './common/event.bus';
Vue.prototype.$Bus = Bus;

/**
 * Fjord helper
 */
import FjordHelper from './common/fjord';
Vue.prototype.Fjord = FjordHelper;

import store from './store';
import mixins from './common/mixins';
import i18n from './common/i18n';

const plugins = [];

function Fjord(options) {
    this.store = null;
    this._mixins = mixins;

    this._init(options);
}

Fjord.use = function(plugin) {
    plugins.push(plugin);

    return this;
};

Fjord.getPlugins = function() {
    return plugins;
};

Fjord.prototype._init = function(options) {
    if ('store' in options) {
        this._store_modules = Object.assign(options.store, this._store_modules);

        for (let i = 0; i < plugins.length; i++) {
            let plugin = plugins[0];
            if (!('store' in plugin)) {
                continue;
            }
            this._store_modules = Object.assign(
                plugin.store,
                this._store_modules
            );
        }
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
        },
        data: {
            fjPlugins: plugins
        }
    });
};

export default Fjord;
