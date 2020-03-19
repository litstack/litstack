import Bus from './event.bus';
import store from '@fj-js/store';

const setBaseUrl = config => {
    window.axios.defaults.baseURL = store.state.config.baseURL;
};

Bus.$on('configSet', setBaseUrl);
