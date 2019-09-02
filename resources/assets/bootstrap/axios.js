// window.axios is set in ./globals.js

import Bus from './../common/event.bus'
import store from './../store';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const setBaseUrl = (config) => {
    window.axios.defaults.baseURL = store.state.config.baseURL
}

Bus.$on('configSet', setBaseUrl)
