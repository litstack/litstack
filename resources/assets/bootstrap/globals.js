window._ = require('lodash');
window.collect = require('collect.js');
window.axios = require('axios');

window.Vue = require('vue');

window.Cropper = require('cropperjs');

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    );
}
