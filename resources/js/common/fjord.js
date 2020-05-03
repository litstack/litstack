import Bus from './event.bus';
import store from '@fj-js/store';

const Fjord = {
    bus: Bus,
    config: {},
    baseURL: null,
    getLocale() {
        return i18n.locale;
    },
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
