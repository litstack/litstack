import Bus from './event.bus';
import store from '@fj-js/store';

const Fjord = {
    bus: Bus,
    config: {},
    baseURL: null,

    /**
     * Get authenticated fjord-user model.
     *
     * @return {Object}
     */
    user() {
        return store.getters.auth;
    },

    /**
     * Get Fjord application locale.
     *
     * @param {Object} obj
     * @return {String}
     */
    getLocale() {
        return i18n.locale;
    },

    /**
     * Check if Fjord application locale is locale.
     *
     * @param {Object} obj
     * @return {Boolean}
     */
    isLocale(locale) {
        return i18n.locale == locale;
    },

    /**
     * Clone object.
     *
     * @param {Object} obj
     */
    clone(obj) {
        return JSON.parse(JSON.stringify(obj));
    }
};

window.Fjord = Fjord;

const setConfig = config => {
    window.Fjord.config = config;
    window.Fjord.baseURL = store.state.config.baseURL;
};

Bus.$on('configSet', setConfig);

export default Fjord;
