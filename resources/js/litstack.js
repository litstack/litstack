require('./common/bootstrap');
require('./common/helpers');
require('./common/axios');
require('./common/window');
require('./common/lit');

require('./service/component.service');
require('./service/library.service');

/**
 * The Litstack Application
 */
import LitstackApp from './LitstackApp';

/**
 * A simple event-bus
 */
import Bus from './common/event.bus';
Vue.prototype.$Bus = Bus;

/**
 * Lit helper
 */
import LitHelper from './common/lit';
Vue.prototype.Lit = LitHelper;

import store from './store';
import mixins from './common/mixins';
import i18n from './common/i18n';

const plugins = [];

function Litstack(options) {
    this.store = null;
    this._mixins = mixins;

    this._init(options);
}

Litstack.use = function (plugin) {
    plugins.push(plugin);

    return this;
};

Litstack.getPlugins = function () {
    return plugins;
};

Litstack.prototype._init = function (options) {
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
        methods: this._mixins,
    });

    store.createStore(this._store_modules);
    this._vue();
};

Litstack.prototype._vue = function () {
    this.app = new Vue({
        el: '#litstack',
        i18n,
        store: store.store,
        components: {
            LitstackApp,
        },
    });
};

export default Litstack;
