import Bus from './event.bus';
import store from '@fj-js/store';

const Fjord = {
    bus: Bus,
    config: {},
    baseURL: null,

    /**
     * Get Fjord application locale.
     *
     * @param {Object} obj
     */
    getLocale() {
        return i18n.locale;
    },

    /**
     * Check if Fjord application locale is locale.
     *
     * @param {Object} obj
     */
    isLocale(locale) {
        return i18n.locale == locale;
    }
};

window.Fjord = Fjord;

const setConfig = config => {
    window.Fjord.config = config;
    window.Fjord.baseURL = store.state.config.baseURL;
};

Bus.$on('configSet', setConfig);

export default Fjord;
