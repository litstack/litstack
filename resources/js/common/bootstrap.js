import Bus from './event.bus';
import axiosMethods from './axios';

window._ = require('lodash');
window.Vue = require('vue');
import store from '@lit-js/store';

window.Cropper = require('cropperjs');

const axios = require('axios');

window.axios = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

window.axios.interceptors.response.use(
    axiosMethods.axiosResponseSuccess,
    axiosMethods.axiosResponseError
);

window._axios = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

Bus.$on('mounted', () => {
    if (!store.getters.debug) {
        return;
    }

    // TODO: Axios need's to prefer a json response in order to read validation erros.
    //
    //window.axios.defaults.headers['Accept'] =
    //  'text/plain, application/json, */*';
});

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window._axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    );
}
