window._ = require('lodash');
window.collect = require('collect.js');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

//require('bootstrap');

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    );
}

/**
 * register base components globally
 */
import upperFirst from 'lodash/upperFirst';
import camelCase from 'lodash/camelCase';
const requireComponent = require.context(
    './components/Basecomponents',
    false,
    /[A-Z]\w+\.(vue|js)$/
);

requireComponent.keys().forEach(fileName => {
    const componentConfig = requireComponent(fileName);
    const componentName = upperFirst(
        camelCase(fileName.replace(/^\.\/(.*)\.\w+$/, '$1'))
    );

    Vue.component(componentName, componentConfig.default || componentConfig);
});
