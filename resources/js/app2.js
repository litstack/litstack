/**
 * app2.js is being loaded in landing pages.
 */

const token = document.head.querySelector('meta[name="csrf-token"]');

window._ = require('lodash');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
