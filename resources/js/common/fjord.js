import Bus from './event.bus';
import store from '@fj-js/store';

const Fjord = {
    event: Bus,
    config: {},
    baseURL: null
};

window.Fjord = Fjord;

const setConfig = config => {
    window.Fjord.config = config;
    window.Fjord.baseURL = store.state.config.baseURL;
};

Bus.$on('configSet', setConfig);
