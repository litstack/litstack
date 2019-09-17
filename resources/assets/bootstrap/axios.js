// window.axios is set in ./globals.js

import Bus from './../common/event.bus'
import store from './../store';

const setBaseUrl = (config) => {
    window.axios.defaults.baseURL = store.state.config.baseURL
}

Bus.$on('configSet', setBaseUrl)
