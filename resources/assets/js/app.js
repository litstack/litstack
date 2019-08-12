require('./bootstrap');

import VueBootstrap from './bootstrap/vue'
VueBootstrap()

import Navigation from './components/Partials/Navigation';
import Notify from './components/Notify';

import store from './store';

import FjordApp from './FjordApp';

const app = new Vue({
    el: '#app',
    store,
    components: {
        FjordApp,
        Navigation,
        Notify,
    }
});
